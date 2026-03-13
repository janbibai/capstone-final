<?php

namespace App\Services;

use App\Models\Patient;

class PatientService
{
  public function register(array $data): Patient
  {
    $query = Patient::where('first_name', $data['first_name'])
      ->where('last_name', $data['last_name'])
      ->where('date_of_birth', $data['date_of_birth'])
      ->where('gender', $data['gender']);

    if (!empty($data['middle_name'])) {
      $query->where('middle_name', $data['middle_name']);
    }

    if (!empty($data['phone'])) {
      $query->where('phone', $data['phone']);
    }

    if ($query->exists()) {
      throw new \Exception('A patient with these details already exists. Your appointment has been created.');
    }

    return Patient::create([
      'first_name' => $data['first_name'],
      'middle_name' => $data['middle_name'] ?? null,
      'last_name' => $data['last_name'],
      'date_of_birth' => $data['date_of_birth'],
      'gender' => $data['gender'],
      'phone' => $data['phone'] ?? null,
      'email' => $data['email'] ?? null,
      'address' => $data['address'] ?? null,
    ]);
  }
}
