<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CustomerRepository
 * @package App\Repositories
 */
class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * Retrieve all customers.
     *
     * @return Collection<Customer>
     */
    public function all(): Collection
    {
        return Customer::all();
    }

    /**
     * Create a new customer.
     *
     * @param array $data
     * @return Customer
     */
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    /**
     * Find a customer by ID.
     *
     * @param int $id
     * @return Customer|null
     */
    public function find(int $id): ?Customer
    {
        return Customer::find($id);
    }

    /**
     * Update a customer.
     *
     * @param int $id
     * @param array $data
     * @return Customer|null
     */
    public function update(int $id, array $data): ?Customer
    {
        $customer = $this->find($id);

        if ($customer) {
            $customer->update($data);
        }

        return $customer;
    }

    /**
     * Delete a customer.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $customer = $this->find($id);

        if ($customer) {
            return $customer->delete();
        }

        return false;
    }
}
