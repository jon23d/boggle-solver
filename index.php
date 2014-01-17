<?

ini_set('display_errors', 'on');
error_reporting(E_ALL);

// create a basic autoloader
spl_autoload_register(function($className) {
    $className = ltrim($className, '\\');
    $fileName  = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
});

// let's just start with the test dictionary
$LinuxDictionary = new \Boggle\Dictionaries\LinuxDictionary();
$Game = new \Boggle\Game($LinuxDictionary, 4, 4);
$Game->outputBoardAsHtml()->outputWordListAsHtml();