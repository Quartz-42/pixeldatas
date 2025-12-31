<?php

namespace App\Command;

use App\API\PokeRequest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-pokemons',
    description: 'Import des Pokémons depuis l\'API vers la BDD',
)]
class ImportPokemonsCommand extends Command
{
    public function __construct(
        private PokeRequest $pokeRequest
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Lancement de l\'import des Pokémons...');

        try {
            $io->text('Récupération des données...');
            
            // Appel du service 
            $pokemons = $this->pokeRequest->fromAPiToObjects();

            $count = count($pokemons);
            
            $io->success(sprintf('Succès ! %d Pokémons ont été sauvegardés.', $count));

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Une erreur est survenue :');
            $io->text($e->getMessage());

            return Command::FAILURE;
        }
    }
}
