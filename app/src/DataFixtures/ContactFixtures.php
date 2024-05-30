<?php
/**
 * Contact fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Contact;

/**
 * Class ContactFixtures.
 */
class ContactFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        $this->createMany(20, 'contacts', function (int $i) {
            $contact = new Contact();
            $contact->setName($this->faker->firstName);
            $contact->setSurname($this->faker->lastName);
            $contact->setAddress($this->faker->address);
            $contact->setPhone($this->faker->numerify('#########'));

            $createdAt = \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 year', 'now'));
            $updatedAt = \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween($createdAt->format('Y-m-d H:i:s'), 'now'));

            $contact->setCreatedAt($createdAt);
            $contact->setUpdatedAt($updatedAt);

            return $contact;
        });

        $this->manager->flush();
    }
}
