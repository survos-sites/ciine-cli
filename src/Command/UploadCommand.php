<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand('ciine:upload', 'upload a Asciinema file or directory to SurvosCiine site', aliases: ['upload'])]
class UploadCommand
{
    public function __construct(
        private readonly HttpClientInterface                        $httpClient,
        #[Autowire('%kernel.project_dir%')] private readonly string $projectDir,
        private array $config = [],
        private ?string $apiEndpoint=null,
    )
    {
    }


    public function __invoke(
        SymfonyStyle                                                      $io,
        #[Argument('path to file or directory')] string                   $path,
        #[Option(name: 'server-url', description: 'api endpoint')] string $apiEndpoint = '',
    ): int
    {
//        SCREENSHOW_ENDPOINT=https://show.survos.com/api/asciicasts

        if (!$apiEndpoint) {
            $apiEndpoint = 'https://showcase.wip/api/asciicasts';
        }
        if (!file_exists($path)) {
            $path = $this->projectDir . $path;
        }
        if (!file_exists($path)) {
            $io->error("$path does not exist");
            return Command::FAILURE;
        }
        $fileHandle = fopen($path, 'r');
        $params = [
//            'content-type' => 'application/json',
            'verify_peer' => false,
            'verify_host' => false,
            'body' => ['asciicast' => $fileHandle]
        ];
        if (str_contains($apiEndpoint, '.wip')) {
            $params['proxy'] = '127.0.0.1:7080';
        }

        $response = $this->httpClient->request('POST', $apiEndpoint, $params);
        if (($statusCode = $response->getStatusCode()) !== 200) {
            $io->error("Api endpoint {$apiEndpoint} not reachable: $statusCode");
        } else {
            $io->writeln($response->getContent(), JSON_PRETTY_PRINT);

            $data = $response->toArray();
            $dl = self::array_map_assoc(fn($var, $val) => [$var => $val], $data);
            $io->definitionList(...$dl);
//
//            $dl = array_walk($data, fn($key, $value) => [$key => $value]);
//            $io->definitionList($dl);
//            static::displayArray($io, $data, "Response");
//            $io->definitionList(...$response->toArray());
//            dump($response->getContent(), $response->toArray());
        }

        $io->success(self::class . " success.");
        return Command::SUCCESS;
    }

    public static function displayArray(SymfonyStyle $io, array $data, string $title = null): void
    {
        if ($title) {
            $io->section($title);
        }

        $definitions = self::arrayToDefinitions($data);
        $io->definitionList(...$definitions);
    }

    private static function array_map_assoc(callable $callback, array $array): array
    {
        return array_map(function($key) use ($callback, $array){
            return $callback($key, $array[$key]);
        }, array_keys($array));
    }

    private static function arrayToDefinitions(array $data): array
    {
        $definitions = [];

        foreach ($data as $key => $value) {
            $formattedKey = ucfirst(str_replace('_', ' ', (string) $key));
            $formattedValue = self::formatValue($value);
            $definitions[] = [$formattedKey, $formattedValue];
        }

        return $definitions;
    }

    private static function formatValue(mixed $value): string
    {
        return match (true) {
            is_null($value) => '<fg=gray>null</>',
            is_bool($value) => $value ? '<fg=green>true</>' : '<fg=red>false</>',
            is_array($value) => '<fg=yellow>[' . count($value) . ' items]</>',
            is_object($value) => '<fg=cyan>' . get_class($value) . '</>',
            is_string($value) && empty($value) => '<fg=gray>(empty)</>',
            is_string($value) => $value,
            default => (string) $value
        };
    }
}
