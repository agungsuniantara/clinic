<?php
require_once('database.php');

class Doctor
{
    private $koneksi;

    public function __construct(Database $koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function createDoctor($id_user, $spesialis)
    {
        $sql = "INSERT INTO doctors VALUES('','$id_user','$spesialis')";
        $result = $this->koneksi->executeQuery($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updateDoctor($id_user, $spesialis)
    {
        $sql = "UPDATE doctors SET Spesialis = '$spesialis' WHERE ID_user = '$id_user'";
        $result = $this->koneksi->executeQuery($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updateStatus($ID_PatientDoctor, $is_finished, $prescription)
    {
        $sql = "UPDATE patient_doctor SET is_finished = '$is_finished', prescription = '$prescription' WHERE ID_PatientDoctor = '$ID_PatientDoctor'";
        $result = $this->koneksi->executeQuery($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function getDoctor($id)
    {
        $sql = "SELECT * FROM doctors WHERE ID_user = '$id'";
        $result = $this->koneksi->executeQuery($sql);

        $row = $result->fetch_assoc();
        return $row;
    }

    public function getFetchDoctor($id)
    {
        $sql = "SELECT users.ID_user, users.Name_user, users.Gender, users.Email, users.Phone, users.Address, users.Date_of_birth, users.Passwords, users.Roles, doctors.Spesialis FROM doctors INNER JOIN users ON doctors.ID_user = users.ID_user WHERE doctors.ID_user = '$id'";
        $result = $this->koneksi->executeQuery($sql);

        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    public function getJoinDoctor($role)
    {
        $sql = "SELECT users.ID_user, users.Name_user, users.Gender, users.Email, users.Phone, users.Address, users.Date_of_birth, users.Passwords, users.Roles, doctors.Spesialis FROM doctors INNER JOIN users ON doctors.ID_user = users.ID_user WHERE users.Roles = '$role'";

        $result = $this->koneksi->executeQuery($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function jadwalPatient($id)
    {
        $sql = "SELECT users_patient.Name_user AS patient_name, patient_doctor.ID_doctor,patient_doctor.ID_patient, patient_doctor.Visit_date, patient_doctor.is_finished, patient_doctor.prescription, patient_doctor.consultatio FROM patient_doctor INNER JOIN doctors ON patient_doctor.ID_doctor = doctors.ID_doctor INNER JOIN patients ON patient_doctor.ID_patient = patients.ID_patient INNER JOIN users AS users_patient ON patients.ID_user = users_patient.ID_user WHERE patient_doctor.ID_doctor = $id";

        $result = $this->koneksi->executeQuery($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    public function getFetchJadwal($id)
    {
        $sql = "SELECT users.Name_user,patient_doctor.ID_PatientDoctor,patient_doctor.ID_doctor, patient_doctor.ID_patient, patient_doctor.ID_user, patient_doctor.Visit_date, patient_doctor.is_finished, patient_doctor.prescription, patient_doctor.consultation FROM patient_doctor INNER JOIN doctors ON patient_doctor.ID_doctor = doctors.ID_doctor INNER JOIN patients ON patient_doctor.ID_patient = patients.ID_patient INNER JOIN users ON patient_doctor.ID_user = users.ID_user WHERE patient_doctor.ID_PatientDoctor = $id";

        $result = $this->koneksi->executeQuery($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }
}
