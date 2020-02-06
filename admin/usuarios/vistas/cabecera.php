<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de usuarios</title>
    
    <link rel="icon" type="image/png" href="/tienda/admin/usuarios/favicon-user.png">
    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <link rel="stylesheet" href="/tienda/admin/usuarios/estilos/css/bootstrap.min.css">
    <link rel="stylesheet" href="/tienda/admin/usuarios/estilos/css/mdb.css">
    <link rel="stylesheet" href="/tienda/admin/usuarios/estilos/css/style.css">
    <script src="/tienda/admin/usuarios/estilos/js/jquery.min.js"></script>
    <script src="/tienda/admin/usuarios/estilos/js/bootstrap.js"></script>

    <style type="text/css">
        * {
            margin: 0px;
            padding: 0px;
        }

        ul,
        ol {
            list-style: none;
        }

        .nav>li {
            float: left;
        }

        .nav li a {
            text-decoration: none;
            padding: 10px 12px;
            display: block;
        }


        .nav li ul {
            display: none;
            position: absolute;
            min-width: 140px;
            background-color: #F5F5F5;
            border-radius: 10%;
        }

        .nav li:hover>ul {
            display: block;
        }
/*CSS para no imprimir las etiquetas que tengan id="no_imprimir"*/
        @media print {
            #no_imprimir {
                display: none
            }
        }

        footer {
		position: absolute;
		bottom: 0;
		width: 100%;
		height: 53px;
	}
    </style>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body>
    <?php require_once "navbar.php"; ?>