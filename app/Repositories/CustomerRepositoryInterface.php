<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface CustomerRepositoryInterface
 * @package App\Repositories
 */
interface CustomerRepositoryInterface
{
    /**
     * Retrieve all customers.
     *
     * @return Collection<Customer>
     */
    public function all(): Collection;

    /**
     * Create a new customer.
     *
     * @param array $data
     * @return Customer
     */
    public function create(array $data): Customer;

    /**
     * Find a customer by ID.
     *
     * @param int $id
     * @return Customer|null
     */
    public function find(int $id): ?Customer;

    /**
     * Update a customer.
     *
     * @param int $id
     * @param array $data
     * @return Customer|null
     */
    public function update(int $id, array $data): ?Customer;

    /**
     * Delete a customer.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
