<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures
{
    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load data.
     */
    protected function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(1, 'admins', function (int $i) {
            $user = new User();
            $user->setEmail('admin@example.com');
            $user->setRoles([UserRole::ROLE_ADMIN->value]);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'admin1'
                )
            );

            return $user;
        });

        $this->manager->flush();
    }
}
