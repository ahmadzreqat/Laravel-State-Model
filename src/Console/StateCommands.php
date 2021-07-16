<?php


namespace statemm\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class StateCommands extends Command
{
    /**
     * @var string
     */
    protected $signature = 'state:make {state} {--dir=}';

    /**
     * @var string
     */
    protected $description = 'create new state class';

    /**
     * @var string
     */
    private $file;
    /**
     * @var string
     */
    private $directory;
    /**
     * @var string
     */
    private $state;

    /**
     * StateCommands constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return false|string
     */
    protected function getDirectoryStateStub()
    {
        return file_get_contents(__DIR__ . "/stubs/dirState.stub");
    }

    /**
     * @return false|string
     */
    protected function getAbstractStateStub()
    {
        return file_get_contents(__DIR__ . "/stubs/abstractState.stub");
    }

    /**
     * @return false|string
     */
    protected function getContextStub()
    {
        return file_get_contents(__DIR__ . "/stubs/context.stub");
    }

    /**
     * @return false|string
     */
    protected function getEnumStub()
    {
        return file_get_contents(__DIR__ . "/stubs/stateEnum.stub");
    }


    /**
     * @return array|false|string|string[]
     */
    private function sanitizeDirectoryStateTemplate()
    {
        return str_replace(
            ['{{STATE}}', '{{ENUM}}', '{{directory}}'],
            [ucfirst($this->state), strtoupper($this->state), $this->directory],
            $this->getDirectoryStateStub()
        );
    }

    /**
     * @return array|false|string|string[]
     */
    private function sanitizeAbstractStateTemplate()
    {
        return str_replace(
            ['{{directory}}'],
            [$this->directory],
            $this->getAbstractStateStub()
        );
    }


    /**
     * @return array|false|string|string[]
     */
    private function sanitizeContextTemplate()
    {
        return str_replace(
            ['{{directory}}'],
            [$this->directory],
            $this->getContextStub()
        );
    }

    /**
     * @param array $enums
     * @return array|false|string|string[]
     */
    private function sanitizeEnumTemplate(array $enums)
    {
        return str_replace(
            ['{{const}}', '{{in_array}}', '{{directory}}'],
            [
                implode(PHP_EOL, array_values($enums)),
                implode(PHP_EOL, array_keys($enums)),
                $this->directory
            ],
            $this->getEnumStub()
        );
    }

    /**
     * @return void
     */
    protected function CreateStateTemplate(): void
    {
        $this->loadDirectoryStateStub();
        $this->setEnumClass();
    }

    /**
     * generate all enum class in directory
     *
     * @return void
     *
     */
    private function setEnumClass(): void
    {
        $DirectoryPath = app_path("/Models/$this->directory");

        $getFiles = scandir($DirectoryPath);

        $sanitizedFiles = array_diff($getFiles, [
            '.',
            '..',
            'Context.php',
            'State.php',
            'StateEnum.php'
        ]);

        $enums = [];

        foreach ($sanitizedFiles as $file) {

            $enumName = strtoupper(Str::before($file, 'State'));

            $generateConst = 'public const ' . $enumName . '_STATE = "' . $enumName . '";';

            $callEnumInArray = 'self::' . $enumName . '_STATE ,';

            $enums[$callEnumInArray] = $generateConst;
        }

        $path = app_path("/Models/$this->directory/StateEnum.php");

        file_put_contents(
            $path,
            $this->sanitizeEnumTemplate($enums)
        );
    }


    /**
     * @return void
     */
    private function loadDirectoryStateStub(): void
    {
        $this->checkDirectoryIsExist();
       // $this->setAbstractStateClassDirectory();
       // $this->setContextClassDirectory();
        $this->setStateClassDirectory();
    }

    /**
     * @return void
     */
    private function checkDirectoryIsExist(): void
    {
        $Directory = app_path("/Models/$this->directory");

        if (!file_Exists($Directory)) {
            mkdir($Directory, 0777, true);
        }

    }

    /**
     * @return void
     */
    private function setAbstractStateClassDirectory(): void
    {
        $path = app_path("/Models/$this->directory/State.php");

        if (!file_exists($path)) {
            file_put_contents(
                $path,
                $this->sanitizeAbstractStateTemplate()
            );
        }
    }

    /**
     * @return void
     */
    private function setContextClassDirectory(): void
    {
        $path = app_path("/Models/$this->directory/Context.php");

        if (!file_exists($path)) {
            file_put_contents(
                $path,
                $this->sanitizeContextTemplate()
            );
        }
    }

    /**
     * @return void
     */
    private function setStateClassDirectory(): void
    {
        $path = app_path(
            $this->file =
                "/Models/$this->directory/" .
                ucfirst($this->state) .
                "State.php"
        );

        file_put_contents(
            $path,
            $this->sanitizeDirectoryStateTemplate()
        );
    }


    /**
     * exec command
     */
    public function handle()
    {
        $this->state = $this->argument('state');

        $this->directory = $this->option('dir');

        if (!$this->option('dir')) {
            throw new InvalidArgumentException(
                'the --dir= is required please set the directory'
            );
        }

        $this->CreateStateTemplate();

        $file = substr($this->file, 1);

        $this->comment("a New $file has been Created");
    }


}
