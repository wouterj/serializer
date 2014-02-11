<?php

use Wj\Serializer\SerializerFactory;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\CustomSnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Behat context class.
 */
class FeatureContext implements CustomSnippetAcceptingContext
{
    private $testDir;
    private $object;
    private $aliases = array();
    private $serializeResult;

    /**
     * Initializes context.
     *
     * Every scenario gets it's own context object.
     * You can also pass arbitrary arguments to the context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    public static function getAcceptedSnippetType() { return 'regex'; }

    /**
     * Cleans test folders in the temporary directory.
     *
     * @BeforeFeature
     * @author Konstantin Kudryashov <ever.zet@gmail.com>
     */
    public static function cleanTestFolders()
    {
        $clearDirectory = null;
        $clearDirectory = function ($path) use (&$clearDirectory) {
            $files = scandir($path);
            array_shift($files);
            array_shift($files);

            foreach ($files as $file) {
                $file = $path.DIRECTORY_SEPARATOR.$file;
                if (is_dir($file)) {
                    $clearDirectory($file);
                } else {
                    unlink($file);
                }
            }

            rmdir($path);
        };
        
        if (is_dir($dir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'serializer')) {
            $clearDirectory($dir);
        }
    }

    /**
     * Prepares test folders in the temporary directory.
     *
     * @BeforeScenario
     * @author Konstantin Kudryashov <ever.zet@gmail.com>
     */
    public function prepareTestFolders()
    {
        $dir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'serializer'.DIRECTORY_SEPARATOR
            .md5(microtime() * rand(0, 10000));

        mkdir($dir, 0777, true);
        chdir($dir);

        mkdir($dir.DIRECTORY_SEPARATOR.'config', 0777, true);

        $this->testDir = $dir;
    }

    /**
     * @Given /^a file called "([^"]*)" with:$/
     */
    public function createFile($fileName, PyStringNode $fileContents)
    {
        file_put_contents($this->testDir.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $fileName), (string) $fileContents);
    }

    /**
     * @Given /^a "([^"]*)" object with (".*?")(?: as "([^"]+)")?$/
     */
    public function initializeObject($classname, $propertiesString, $alias = null)
    {
        eval(file_get_contents($this->testDir.DIRECTORY_SEPARATOR.$classname.'.php'));
        if (null !== $alias) {
            $this->aliases[$alias] = $object = new $classname;
        } else {
            $this->object = $object = new $classname;
        }

        $properties = explode(' and ', $propertiesString);
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($properties as $propertyString) {
            list($propAccess, $value) = explode(' = ', trim($propertyString, '"'));

            if ('%' === substr($value, 0, 1)) {
                $value = isset($this->aliases[substr($value, 1, -1)]) ? $this->aliases[substr($value, 1, -1)] : null;
            } else {
                $value = json_decode(strtr($value, "'", '"'));
            }
            $accessor->setValue($object, $propAccess, $value);
        }
    }

    /**
     * @When /^I serialize the object in the "([^"]*)" format$/
     */
    public function serializeObject($format)
    {
        $serializer = $this->getSerializer();

        $this->serializeResult = $serializer->serialize($format, $this->object);
    }

    /**
     * @Then /^the result should be:$/
     */
    public function assertResult(PyStringNode $expectedResult)
    {
        \PHPUnit_Framework_Assert::assertEquals((string) $expectedResult, $this->serializeResult);
    }

    protected function getSerializer()
    {
        $builder = SerializerFactory::createSerializerBuilder()
            ->setFileLocator(new FileLocator($this->testDir.DIRECTORY_SEPARATOR.'config'));

        $formats = array(
            'json' => 'Wj\Serializer\Formatter\Json',
        );
        foreach ($formats as $formatName => $formatterClassname) {
            $builder->registerFormat('json', new $formatterClassname);
        }

        return $builder->getSerializer();
    }
}
