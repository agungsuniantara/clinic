<?php
require_once('database.php');

class User
{
    private $koneksi;

    public function __construct(Database $koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function authenticate($email, $password)
    {
        $sql = "SELECT * FROM users WHERE Email = '$email' AND Passwords = '$password'";
        $result = $this->koneksi->executeQuery($sql);

        if ($result->num_rows > 0) {
            return true;
        }

        return false;
    }

    public function getUserData($email)
    {
        $sql = "SELECT * FROM users WHERE Email = '$email'";
        $result = $this->koneksi->executeQuery($sql);

        $userData = $result->fetch_assoc();

        return $userData;
    }

    public function getJoinUser()
    {
        $sql = "SELECT * FROM users";
        $result = $this->koneksi->executeQuery($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function updateUser($id, $name, $gender, $email, $phone, $address, $date, $password, $role)
    {
        $sql = "UPDATE users SET Name_user = '$name', Gender = '$gender', Email = '$email', Phone = '$phone', Address = '$address', Date_of_birth = '$date', Passwords = '$password', Roles = '$role' WHERE ID_user = '$id'";

        $result = $this->koneksi->executeQuery($sql);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE ID_user='$id'";
        $result = $this->koneksi->executeQuery($sql);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function createUser($name, $gender, $email, $phone, $address, $date, $password, $role)
    {
        $sql = "INSERT INTO users VALUES('','$name','$gender','$email','$phone','$address','$date','$password','$role')";
        $result = $this->koneksi->executeQuery($sql);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function jadwalPatient()
    {
        $sql = "SELECT users_patient.Name_user AS patient_name, users_doctor.Name_user AS doctor_name, patient_doctor.Visit_date, patient_doctor.is_finished, patient_doctor.prescription, patient_doctor.consultation, patient_doctor.ID_PatientDoctor 
        FROM patient_doctor 
        INNER JOIN doctors ON patient_doctor.ID_doctor = doctors.ID_doctor 
        INNER JOIN patients ON patient_doctor.ID_patient = patients.ID_patient 
        INNER JOIN users AS users_patient ON patients.ID_user = users_patient.ID_user 
        INNER JOIN users AS users_doctor ON doctors.ID_user = users_doctor.ID_user";

        $result = $this->koneksi->executeQuery($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    public function fetchName()
    {
        $sql = "SELECT 'Doctor' AS status, users.Name_user 
        FROM doctors 
        INNER JOIN users ON doctors.ID_user = users.ID_user
        UNION
        SELECT 'Patient' AS status, users.Name_user 
        FROM patients 
        INNER JOIN users ON patients.ID_user = users.ID_user";

        $result = $this->koneksi->executeQuery($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }
}
