<?php

declare(strict_types = 1);

namespace App\Command;

use App\Repositories\UserRepositoryInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\QuestionHelper;

class GetUserCommand
{

    private QuestionHelper $helper;
    private UserRepositoryInterface $userRepository;

    public function __construct(QuestionHelper $helper, UserRepositoryInterface $userRepository)
    {
        $this->helper = $helper;
        $this->userRepository = $userRepository;
    }

    public function __invoke(InputInterface $input, OutputInterface $output): void
    {
        $user_id = $input->getArgument('user_id');
        
        if (! $user_id) {
            
            $question = new Question('Please enter \'user_id\': ', false);
            $question->setValidator(static function ($answer) {
                
                if (! $answer || ! is_string($answer)) {
                    
                    throw new \RuntimeException(
                        'The user_id is empty!'
                    );
                }

                return $answer;
            });
            $question->setMaxAttempts(2);
            $user_id = $this->helper->ask($input, $output, $question);
        }
        $user = $this->userRepository->getUser($user_id);
        $output->writeln(sprintf('UserName: <info>%s</info>', $user->getName()));
    }
    
}

