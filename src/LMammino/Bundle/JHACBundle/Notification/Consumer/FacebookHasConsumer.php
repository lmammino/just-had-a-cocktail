<?php

namespace LMammino\Bundle\JHACBundle\Notification\Consumer;

use Sonata\NotificationBundle\Consumer\ConsumerEvent;
use Sonata\NotificationBundle\Consumer\ConsumerInterface;
use Sonata\NotificationBundle\Model\MessageInterface;
use Sonata\NotificationBundle\Exception\InvalidParameterException;

use LMammino\Bundle\JHACBundle\Facebook\Client;
use LMammino\Bundle\JHACBundle\Entity\CocktailRepository;
use LMammino\Bundle\JHACBundle\Entity\UserRepository;
use LMammino\Bundle\JHACBundle\Entity\Cocktail;
use LMammino\Bundle\JHACBundle\Entity\User;

class FacebookHasConsumer implements ConsumerInterface
{
    /**
     * @var \LMammino\Bundle\JHACBundle\Facebook\Client $client
     */
    protected $client;

    /**
     * @var CocktailRepository $cocktailRepository
     */
    protected $cocktailRepository;

    /**
     * @var UserRepository $userRepository
     */
    protected $userRepository;

    /**
     * @var string $appUrl
     */
    protected $appUrl;


    public function __construct(Client $client, CocktailRepository $cocktailRepository, UserRepository $userRepository, $appUrl)
    {
        $this->client = $client;
        $this->cocktailRepository = $cocktailRepository;
        $this->userRepository = $userRepository;
        $this->appUrl = $appUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ConsumerEvent $event)
    {
        $message = $event->getMessage();

        $userId = $message->getValue('user_id');
        $cocktailId = $message->getValue('cocktail_id');
        $timestamp = $message->getValue('date');

        $user = $this->userRepository->findOneById($userId);
        if(!$user instanceof User)
            throw new InvalidParameterException('invalid user id');

        $cocktail = $this->cocktailRepository->findOneById($cocktailId);
        if(!$cocktail instanceof Cocktail)
            throw new InvalidParameterException('invalid cocktail id');

        if($user->isConnectedWithFacebook())
        {
            $accessToken = $user->getFacebookAccessToken();
            $cocktailUrl = $this->appUrl . $cocktail->getRelativeUrl();
            $this->client->setAccessToken($accessToken);
            $response = $this->client->hadCocktail($cocktailUrl, $timestamp);
            // TODO analyze response and log errors
        }
    }
}
