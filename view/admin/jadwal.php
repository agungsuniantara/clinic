<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" />
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

    <style>
        #wrapper {
            overflow-x: hidden;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
            transition: margin 0.25s ease-out;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }

        #sidebar-wrapper .list-group {
            width: 15rem;
        }

        #page-content-wrapper {
            min-width: 100vw;
        }

        body.sb-sidenav-toggled #wrapper #sidebar-wrapper {
            margin-left: 0;
        }

        #profile-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: auto;
            background-image: url(https://s.hdnux.com/photos/51/23/24/10827008/4/1200x0.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border: none;
            padding: 0;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            body.sb-sidenav-toggled #wrapper #sidebar-wrapper {
                margin-left: -15rem;
            }
        }

        /* Style for the "Dropdown" image */

        .dropdown-image:hover {
            background-color: #777;
        }
    </style>
</head>

<body>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="home.php">Home</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="datauser.php">Data user</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="dokter.php">Dokter</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="pasien.php">Pasien</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="jadwal.php">Jadwal</a>
            </div>
        </div>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-secondary" id="sidebarToggle">Menu</button>
                    <button id="profile-circle" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <div class="d-lg-none">
                                <li><a class="dropdown-item" href="../../logout.php">Logout</a></li>
                            </div>
                            <div class="d-none d-lg-block">
                                <div class="dropdown">
                                    <button id="profile-circle" type="button" class="btn" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="../../logout.php">Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Page content-->
            <div class="container-fluid">
                <h1 class="mt-4"></h1>
                <table id="datatable" class="display responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th>Patient name</th>
                            <th>Doctor name</th>
                            <th>Visit date</th>
                            <th>Consultation</th>
                            <th>Prescription</th>
                            <th>Finished</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambah" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Tambah jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDataForm" enctype="multipart/form-data" method="POST">
                        <div class="mb-3">
                            <label for="patient_name" class="col-form-label">Patient : </label>
                            <select name="patient_name" id="patient_name" class="form-select" required>
                                <option></option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="doctor_name" class="col-form-label">Doctor : </label>
                            <select name="doctor_name" id="doctor_name" class="form-select" required>
                                <option></option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="Visit_date" class="col-form-label">Visit date</label>
                            <input type="date" class="form-control" id="Visit_date" name="Visit_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="consultation" class="col-form-label">Consultation</label>
                            <input type="text" class="form-control" id="consultation" name="consultation" placeholder="Consultation" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Edit jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDataForm" enctype="multipart/form-data" method="POST">
                        <input type="text" class="form-control" id="id" name="ID_PatientDoctor" hidden>
                        <div class="mb-3">
                            <label for="Visit_date" class="col-form-label">Patient</label>
                            <input type="text" class="form-control" id="name" name="Name_user" placeholder="Patient name" required>
                        </div>
                        <div class="mb-3">
                            <label for="Visit_date" class="col-form-label">Doctor</label>
                            <input type="text" class="form-control" id="name" name="Name_user" placeholder="Patient name" required>
                        </div>
                        <div class="mb-3">
                            <label for="Visit_date" class="col-form-label">Visit date</label>
                            <input type="date" class="form-control" id="name" name="Visit_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="consultation" class="col-form-label">Consultation</label>
                            <input type="text" class="form-control" id="name" name="consultation" placeholder="Consultation" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $("#datatable").DataTable({
                responsive: true,
                ajax: {
                    url: "../../controller/main.php",
                    type: "POST",
                    data: {
                        action: "jadwal",
                    },
                    dataType: "json",
                },
                columns: [{
                        data: "patient_name"
                    },
                    {
                        data: "doctor_name"
                    },
                    {
                        data: "Visit_date"
                    },
                    {
                        data: "consultation"
                    },
                    {
                        data: "prescription"
                    },
                    {
                        data: "is_finished"
                    },
                    {
                        data: "ID_PatientDoctor",
                        render: function(data) {
                            return "<div style='display: flex; gap: 5px;'><button onclick='deleteData(" + data + ")' class='btn btn-danger'>Delete</button>" + "<button onclick='editData(" + data + ")' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#edit'>Edit</button></div>";
                        },
                    },
                ],
            });
            // Tambahkan tombol di bawah kolom pencarian
            var button = $("<div></div>").insertAfter(
                ".dataTables_wrapper .dataTables_filter input"
            );

            // Tambahkan tombol di sini
            button.append(
                "<button class='btn btn-primary' onclick='tambahData()' data-bs-toggle='modal' data-bs-target='#tambah'>Tambah</button>"
            );

            button.css({
                "margin-top": "20px",
            });
        });

        function editData(ID_PatientDoctor) {
            $.ajax({
                type: "POST",
                url: "../../controller/main.php",
                data: {
                    action: "fetch",
                    id: ID_PatientDoctor,
                    role: "doctor",
                },
                dataType: "json",
                success: function(response) {
                    $("#editDataForm input[name='ID_PatientDoctor']").val(response[0]["ID_PatientDoctor"]);
                    $("#editDataForm select[name='is_finished']").val(response[0]["is_finished"]);
                    $("#editDataForm").submit(function(event) {
                        event.preventDefault();
                        var formData = new FormData(this);
                        formData.append("action", "updateStatus");
                        formData.append("id", ID_PatientDoctor);

                        $.ajax({
                            type: "POST",
                            enctype: "multipart/form-data",
                            url: "../../controller/main.php",
                            data: formData,
                            contentType: false,
                            processData: false,
                            cache: false,
                            dataType: "json",
                            success: function(response) {
                                alert(response.message);
                                $("#datatable").DataTable().ajax.reload();
                                location.reload();
                            },
                            error: function(response) {
                                alert(response);
                            },
                        });
                    });
                },
                error: function(response) {
                    alert(response);
                },
            });
        }

        function tambahData() {
            $.ajax({
                type: "POST",
                url: "../../controller/main.php",
                data: {
                    action: "fetchName",
                },
                dataType: "json",
                success: function(response) {
                    $.each(response.data, function(index, item) {
                        // Periksa apakah opsi sudah ada sebelum menambahkannya
                        if ($("#patient_name option[value='" + item.Name_user + "']").length === 0 &&
                            $("#doctor_name option[value='" + item.Name_user + "']").length === 0) {
                            var option = $("<option></option>").text(item.Name_user).attr("value", item.Name_user);
                            if (item.status == "Patient") {
                                option.appendTo("#patient_name");
                            } else if (item.status == "Doctor") {
                                option.appendTo("#doctor_name");
                            }
                        }
                    });

                    $("#addDataForm").submit(function(event) {
                        event.preventDefault();

                        var formData = new FormData(this);

                        formData.append("action", "jadwal");

                        $.ajax({
                            type: "POST",
                            enctype: "multipart/form-data",
                            url: "../../controller/main.php",
                            data: formData,
                            contentType: false,
                            processData: false,
                            cache: false,
                            dataType: "json",
                            success: function(response) {
                                $("#datatable").DataTable().ajax.reload();
                                location.reload();
                            },
                            error: function(response) {
                                alert(response);
                            },
                        });
                    });
                },
                error: function(response) {
                    alert(response);
                },
            });
        }



        $(document).ready(function() {
            // Toggle the side navigation
            $("#sidebarToggle").click(function(event) {
                event.preventDefault();
                $("body").toggleClass("sb-sidenav-toggled");
                localStorage.setItem("sb|sidebar-toggle", $("body").hasClass("sb-sidenav-toggled"));
            });
        });
    </script>
</body>

</html>