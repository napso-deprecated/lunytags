### Testing 
 
- we use orchestra/testbench and phpunit/phpunit
- create abstract class TestCase.php and extend Orchestra\Testbench\TestCase
    - setup service provider 
    - migrate the database (will create the tags and the taggable tables )
    - in getEnvironmentSetUp we setup an sqlite in memory test database and create a pages table so we can tag a page (or any other taggable model)
    - autoload the files created in composer with 
            
            "autoload-dev": {
            "classmap": [
              "tests/TestCase.php",
              "tests/stubs/TagStub.php",
              "tests/stubs/PageStub.php"
            ]
    - add  a phpunit.xml file
    - create stubs (Page and Tag) inside stubs folder to use the models 
    - add the tests e.g in LunyTagsStringUsageTest class 
    - run the tests with vendor/bin/phpunit 
  
  
    
    
     
    
