<?php
require_once('database.php');

class Patient
{
    private $koneksi;

    public function __construct(Database $koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function createPatient($id_user, $asuransi)
    {
        $sql = "INSERT INTO patients VALUES('','$id_user','$asuransi')";
        $result = $this->koneksi->executeQuery($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updatePatient($id_user, $asuransi)
    {
        $sql = "UPDATE patients SET Asuransi = '$asuransi' WHERE ID_user = '$id_user'";
        $result = $this->koneksi->executeQuery($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function getFetchPatient($id)
    {
        $sql = "SELECT users.ID_user, users.Name_user, users.Gender, users.Email, users.Phone, users.Address, users.Date_of_birth, users.Passwords, users.Roles, patients.Asuransi FROM patients INNER JOIN users ON patients.ID_user = users.ID_user WHERE patients.ID_user = '$id'";
        $result = $this->koneksi->executeQuery($sql);

        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    public function getJoinPatient($role)
    {
        $sql = "SELECT users.ID_user, users.Name_user, users.Gender, users.Email, users.Phone, users.Address, users.Date_of_birth, users.Passwords, users.Roles, patients.Asuransi FROM patients INNER JOIN users ON patients.ID_user = users.ID_user WHERE users.Roles = '$role'";

        $result = $this->koneksi->executeQuery($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function getPatient($id)
    {
        $sql = "SELECT * FROM patients WHERE ID_user = '$id'";
        $result = $this->koneksi->executeQuery($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    public function jadwalDoctor($id)
    {
        $sql = "SELECT users.Name_user,patient_doctor.ID_PatientDoctor, patient_doctor.ID_doctor, patient_doctor.ID_patient, patient_doctor.ID_user, patient_doctor.Visit_date, patient_doctor.is_finished, patient_doctor.prescription, patient_doctor.consultation FROM patient_doctor INNER JOIN doctors ON patient_doctor.ID_doctor = doctors.ID_doctor INNER JOIN patients ON patient_doctor.ID_patient = patients.ID_patient INNER JOIN users ON patient_doctor.ID_user = users.ID_user WHERE patient_doctor.ID_patient = $id";

        $result = $this->koneksi->executeQuery($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
}
