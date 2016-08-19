# Implementing your own `Reader` and `Writer`

If you want to use this library, you will most probably have to extend the classes `Reader` and `Writer`,
providing your own implementations.

There is currently one `Reader` implementation in the library, `Csv`, and two `Writer` implementations, `Csv` and `Kml`.
Have a look at the [source code](/src) to see the details of the implementations.

## Extending the class `Reader`

Let's create a fake `Reader` that retrieves information from an external API that doesn't allow batch requests.
Or maybe it does, but will still want to access the results from a foreach loop.

These are the basic steps that we have to take:

1. Extend `JLSalinas\RWGen\Reader`

    ```php
    use JLSalinas\RWGen\Reader;

    class ExternalApiLeecher extends Reader {
    }
    ```

2. Provide some constants and default options (see trait [WithOptions](/src/JLSalinas/RWGen/WithOptions.php) for more information):

    ```php
        public static $default_options = array (
            'sleep_between_calls' => 10 // we know we can be banned if we make too many requests too fast
        );
        
        private static $user = 'XXXXXXXX';
        private static $secret = 'YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY';
        private static $login_url_format = 'https://example.com/api/login?user=%s&secret=%s';
        private static $details_url_format = 'https://example.com/api/details?token=%s&id=%s';
        private static $logout_url_format = 'https://example.com/api/logout?token=%s';
    ```

    Note that default options are set at class level, that's why they are static.

    We don't want users to be able to modify the endpoint or the credentials, so we place those variables out of the default options array.

3. Define a constructor that accepts the IDs (directly with an array, from a local file, whatever) and options

    ```php
        private $ids = null;
        
        public function __construct ($ids, $options = array()) {
            $this->ids = $ids;
            $this->setOptions($options);
        }
    ```

4. Define a [generator](http://php.net/manual/en/language.generators.overview.php) method
with no arguments that connects and retrieves data as many times as needed

    ```php
        private function getData () {
            // General structure of this function is...
            
            // 1. prepare
            $delay = $this->getOption('sleep_between_calls');
            $token = file_get_contents(sprintf(static::$login_url_format, static::$user, static::$secret));
            // checks, lots of cheks...
            
            // 2. repeat
            foreach ( $this->ids as $id ) {
                $data = file_get_contents(sprintf(static::$details_url_format, $token, $id));
                // checks everywhere...
                
                yield $data;
                
                if ( $delay ) {
                    sleep($delay);
                }
            }
            
            // 3. clean-up
            $response = file_get_contents(sprintf(static::$logout_url_format, $token));
            // some more checks...
        }
    ```

    Since this function cannot accept arguments, the way to pass data to it is via the constructor's arguments and class/instance attributes.
    That's why the list of IDs and options were passed to the constructor and stored to be used here.
    
    If the hypotetical API allowed us to retrieve all the data at once, the process would be:
    
    - Constructor:
        - Accept credentials
    - Preparation:
        - Login to external webservice
        - Retrieve all data at once
        - Save data to a temporary file
        - Disconnect from the external service
    - Repeat
        - Iterate over the temporary file -with a nested JLSalinas\RWGen\Reader, like Csv
    - Clean-up
        - Delete temporary file

5. Define a method named `inputGenerator` that returns the generator method

    ```php
        protected function inputGenerator () {
            return $this->getData();
        }
    ```

    This could seem a little bit superfluous -all the code of getData() could be place in inputGenerator() instead-
    but it's useful to keep the real code separated, especially when there are lots of private helper methods in the class.

## Extending the class `Writer`

Let's create a fake `Writer` that logs data to a file. It is just an educational example. Please, creators and adopters of
[PSR-3](http://www.php-fig.org/psr/psr-3/), have mercy on us.

These are the basic steps that we have to take:

1. Extend `JLSalinas\RWGen\Writer`

    ```php
    use JLSalinas\RWGen\Writer;

    class SimpleLogger extends Writer {
    }
    ```

2. Provide some constants and default options (see trait `WithOptions` for more information):

    ```php
        const LEVEL_INFO    = 0;
        const LEVEL_WARNING = 1;
        const LEVEL_ERROR   = 2;

        public static $default_options = array (
            'overwrite' => false,
            'min_level' => static::LOGGER_WARNING
        );
    ```

3. Define a constructor that accepts the IDs (directly with an array, from a local file, whatever) and options

    ```php
        private $outputfile = null;
        
        public function __construct ($outputfile, $options = array()) {
            $this->outputfile = $outputfile;
            $this->setOptions($options);
            
            if ( !$this->getOption('overwrite') && file_exists($outputfile) ) {
                throw new \Exception('Output file already exists: ' . $this->outputfile);
            }
        }
    ```

4. Define a generator with no arguments that writes each line to the file

    ```php
        private function saveLines () {
            // General structure of this function is...
            
            // 1. prepare
            $min_level = $this->getOption('min_level');
            $fh = fopen($this->outputfile, 'w');
            if ( !$fh ) {
                throw new \Exception('Could not open output file: ' . $this->outputfile);
            }
            fwrite($fh, 'Log starts at ' . date('c') . '.');
            
            // 2. repeat
            while ( ($data = yield) !== null ) {
                // check that $data has items 'level' and 'msg'
                
                if ( $data['level'] < $min_level ) {
                    continue;
                }
                
                $num = fwrite($fh, '[' . date('c') . '] ' . $data['msg']);
                // checks, lots of cheks...
            }
            
            // 3. clean-up
            fwrite($fh, 'Log ends at ' . date('c') . '.');
            // some more checks...
            fclose($fh);
        }
    ```

5. Define a method named `outputGenerator` that returns the generator method

    ```php
        protected function outputGenerator () {
            return $this->saveLines();
        }
    ```
