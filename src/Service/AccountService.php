<?php declare(strict_types=1);


namespace App\Service;

use App\Exception\NotEqualsPasswordException;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountService
 * @package App\Service
 */
class AccountService
{
    /**
     * @var ObjectRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AccountService constructor.
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * @param int $userId
     * @param Request $request
     * @return void
     */
    public function update(int $userId, Request $request): bool
    {
        $user = $this->userRepository->find($userId);

        return $this->userRepository->save($user, [
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'location' => $request->get('location'),
            'website' => $request->get('website'),
        ]);
    }

    /**
     * @param int $userId
     * @param Request $request
     * @return bool
     * @throws NotEqualsPasswordException
     */
    public function changePassword(int $userId, Request $request): bool
    {
        if ($request->get('password') !== $request->get('confirmPassword')) {
            throw new NotEqualsPasswordException('Пароль и подтвержение пароля не совпадают');
        }
        $user = $this->userRepository->find($userId);
        $encodePassword = $this->encoder->encodePassword($user, $request->get('password'));

        return $this->userRepository->changePassword($user, $encodePassword);
    }
}