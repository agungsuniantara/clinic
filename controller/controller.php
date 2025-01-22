<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../model/user.php';
require_once '../model/database.php';
require_once '../model/patient.php';
require_once '../model/doctor.php';

class Controller
{
    private $koneksi;

    public function __construct(Database $koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function login($email, $password)
    {
        $user = new User($this->koneksi);

        $auth_user = $user->authenticate($email, $password);

        if ($auth_user) {
            $data_user = $user->getUserData($email);

            return ["id" => $data_user['ID_user'], "nama" => $data_user['Name_user'], "role" => $data_user['Roles']];
        }
        return false;
    }

    public function read($roles)
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(["message" => "not_authenticated"]);
            exit();
        }

        $userRole = $_SESSION['user']['role'];

        switch ($userRole) {
            case 'admin':
                if ($roles == "doctor") {
                    $dokter = new Doctor($this->koneksi);
                    $doctorData = $dokter->getJoinDoctor($roles);

                    echo json_encode(["data" => $doctorData]);
                    exit();
                } else if ($roles == "patient") {
                    $patient = new Patient($this->koneksi);
                    $doctorData = $patient->getJoinPatient($roles);

                    echo json_encode(["data" => $doctorData]);
                    exit();
                } else if ($roles == "all") {
                    $user = new User($this->koneksi);
                    $userData = $user->getJoinUser();

                    echo json_encode(["data" => $userData]);
                    exit();
                }
                break;
            default:
                echo json_encode(["message" => "invalid_role"]);
                exit();
        }
    }

    public function fetch($id, $role)
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(["message" => "not_authenticated"]);
            exit();
        }

        $userRole = $_SESSION['user']['role'];

        switch ($userRole) {
            case 'admin':
                if ($role == "doctor") {
                    $doctor = new Doctor($this->koneksi);
                    $doctorData = $doctor->getFetchDoctor($id);
                    echo json_encode($doctorData);
                    exit();
                } else if ($role == "patient") {
                    $patient = new Patient($this->koneksi);
                    $patientData = $patient->getFetchPatient($id);
                    echo json_encode($patientData);
                    exit();
                }
                break;
            case 'doctor':
                if ($role == "doctor" || $role == "patient") {
                    $jadwal = new Doctor($this->koneksi);
                    $jadwalData = $jadwal->getFetchJadwal($id);
                    echo json_encode($jadwalData);
                    exit();
                }
                break;
            default:
                echo json_encode(["message" => "invalid_role"]);
                exit();
        }
    }

    public function create($akun)
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(["message" => "not_authenticated"]);
            exit();
        }

        $name = $_POST['Name_user'];
        $gender = $_POST['Gender'];
        $email = $_POST['Email'];
        $phone = $_POST['Phone'];
        $address = $_POST['Address'];
        $date = $_POST['Date_of_birth'];
        $password = $_POST['Passwords'];
        $role = $_POST['Roles'];

        if ($akun == "doctor") {
            $optional = $_POST['Spesialis'];
        } else if ($akun == "patient") {
            $optional = $_POST['Asuransi'];
        }

        $user = new User($this->koneksi);
        $userData = $user->createUser($name, $gender, $email, $phone, $address, $date, $password, $role);
        $data_user = $user->getUserData($email);

        if ($akun == "doctor") {
            $dokter = new Doctor($this->koneksi);
            $data = $dokter->createDoctor($data_user['ID_user'], $optional);
        } else if ($akun == "patient") {
            $patient = new Patient($this->koneksi);
            $data = $patient->createPatient($data_user['ID_user'], $optional);
        }

        if ($userData && $data) {
            echo json_encode(["message" => "berhasil ditambah"]);
            exit();
        }
    }

    public function update($id)
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(["message" => "not_authenticated"]);
            exit();
        }

        $name = $_POST['Name_user'];
        $gender = $_POST['Gender'];
        $email = $_POST['Email'];
        $phone = $_POST['Phone'];
        $address = $_POST['Address'];
        $date = $_POST['Date_of_birth'];
        $password = $_POST['Passwords'];
        $role = $_POST['Roles'];

        $user = new User($this->koneksi);
        $userData = $user->updateUser($id, $name, $gender, $email, $phone, $address, $date, $password, $role);

        if ($role == "doctor") {
            $optional = $_POST['Spesialis'];
            $dokter = new Doctor($this->koneksi);
            $dokterData = $dokter->updateDoctor($id, $optional);

            if ($userData && $dokterData) {
                echo json_encode(["message" => "berhasil diupdate"]);
                exit();
            }
        } else if ($role == "patient") {
            $optional = $_POST['Asuransi'];
            $patient = new Patient($this->koneksi);
            $patientData = $patient->updatePatient($id, $optional);

            if ($userData && $patientData) {
                echo json_encode(["message" => "berhasil diupdate"]);
                exit();
            }
        }
    }

    public function delete($id)
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(["message" => "not_authenticated"]);
            exit();
        }

        $user = new User($this->koneksi);
        $result = $user->deleteUser($id);

        if ($result) {
            echo json_encode(["message" => "berhasil delete"]);
        } else {
            echo json_encode(["message" => "gagal delete"]);
        }
    }

    public function jadwal()
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(["message" => "not_authenticated"]);
            exit();
        }

        $userRole = $_SESSION['user']['role'];
        $id = $_SESSION['user']['id'];

        switch ($userRole) {
            case 'admin':
                $user = new User($this->koneksi);
                $jadwal = $user->jadwalPatient();
                echo json_encode(["data" => $jadwal]);
                exit();
                break;
            case 'doctor':
                $doctor = new Doctor($this->koneksi);
                $id_doctor = $doctor->getDoctor($id);
                $jadwal_patient = $doctor->jadwalPatient($id_doctor['ID_doctor']);
                echo json_encode(["data" => $jadwal_patient]);
                exit();
                break;
            case 'patient':
                $patient = new Patient($this->koneksi);
                $id_patient = $patient->getPatient($id);
                $jadwal_dokter = $patient->jadwalDoctor($id_patient['ID_patient']);
                echo json_encode(["data" => $jadwal_dokter]);
                exit();
                break;
            default:
                echo json_encode(["message" => "invalid_role"]);
                exit();
        }
    }

    public function updateStatus($id)
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(["message" => "not_authenticated"]);
            exit();
        }

        $status = $_POST['is_finished'];
        $prescription = $_POST['prescription'];
        $userRole = $_SESSION['user']['role'];

        if ($userRole == "doctor") {
            $jadwal = new Doctor($this->koneksi);
            $jadwalData = $jadwal->updateStatus($id, $status, $prescription);

            if ($jadwalData) {
                echo json_encode(["message" => "berhasil diupdate"]);
                exit();
            }
        }
    }

    public function getName()
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(["message" => "not_authenticated"]);
            exit();
        }

        $user = new User($this->koneksi);
        $jadwal = $user->fetchName();
        echo json_encode(["data" => $jadwal]);
    }
}
