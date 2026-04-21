<?php

namespace App\Services;

use App\Models\Patient;

class PatientService
{
    public function register(array $data, $validIdFile = null): Patient
    {
      $purok = $data['purok'] ?? null;
      $barangay = $data['barangay'] ?? null;
      $addressParts = [];
      if ($purok) $addressParts[] = 'Purok ' . $purok;
      if ($barangay) $addressParts[] = $barangay;
      $address = implode(', ', $addressParts) ?: null;

      $exists = Patient::where('first_name', $data['first_name'])
      ->where('middle_name', $data['middle_name'] ?? null)
      ->where('last_name', $data['last_name'])
      ->where('date_of_birth', $data['date_of_birth'])
      ->where('gender', $data['gender'])
      ->where('phone', $data['phone'])
      ->where('email', $data['email'] ?? null)
      ->where('address', $address)
      ->exists();
    
      if ($exists){
            throw new \Exception('You have already booked');
        }

      // Store the valid ID file
      $validIdPath = null;
      if ($validIdFile) {
          $validIdPath = $validIdFile->store('valid_ids', 'public');
      }

      return Patient::create([
        'first_name' => $data['first_name'],
        'middle_name' => $data['middle_name'] ?? null,
        'last_name' => $data['last_name'],
        'date_of_birth' => $data['date_of_birth'],
        'gender' => $data['gender'],
        'phone' => $data['phone'],
        'email' => $data['email'] ?? null,
        'address' => $address,
        'valid_id_path' => $validIdPath,
      ]);
        
    }
}
