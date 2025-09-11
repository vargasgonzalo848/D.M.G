<!DOCTYPE html>
<html lang="es">
<head>
    <?php include("includes/header.php"); ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Servicios Odontológicos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/servicios.css">
</head>
<body>

<section class="hero text-center text-white py-5 shadow-sm" 
         style="background: linear-gradient(135deg, #0d6efd, #20c997);">
  <div class="container">
    <img src="imagen/Captura_de_pantalla_2025-05-21_124449-removebg-preview.png" 
         alt="Logo Odontología" 
         class="mb-3 bg-white p-2 rounded-circle shadow"
         style="max-width: 120px;">

    <h1 class="display-4 fw-bold">Bienvenido a Odontología Fanny</h1>
    <p class="lead">Cuidamos tu sonrisa con los mejores especialistas y tecnología avanzada</p>
  </div>
</section>

  <section id="services" class="py-5">
    <div class="container">
      <h2 class="text-center mb-5">Nuestros Servicios</h2>
      <div class="row g-4">

        <div class="col-md-4">
          <div class="card service-card shadow-sm">
            <img src="https://clinicadentaldrmestres.com/wp-content/uploads/2023/04/limpieza-dental-antes-despues-1500x750.jpg" class="card-img-top" alt="Limpieza dental">
            <div class="card-body">
              <h5 class="card-title">Limpieza Dental</h5>
              <p class="card-text">Eliminamos placa y sarro para mantener tus dientes saludables y tu sonrisa brillante.</p>
              <p class="price">UYU 2.500 por sesión</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card service-card shadow-sm">
            <img src="https://institutodentallebron.com/wp-content/uploads/2019/05/Diferencia-entre-un-blanqueamiento-en-clinica-y-un-blanqueamiento-en-casa-Instituto-Dental-Lebro%CC%81n-clinica-dental-sevilla.jpg" class="card-img-top" alt="Blanqueamiento">
            <div class="card-body">
              <h5 class="card-title">Blanqueamiento Dental</h5>
              <p class="card-text">Recupera el color natural de tus dientes y mejora tu confianza al sonreír.</p>
              <p class="price">UYU 6.000 por sesión</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card service-card shadow-sm">
            <img src="https://www.teeth22.com/wp-content/uploads/2018/11/cuidar-los-dientes-durante-tratamiento-ortodoncia-1024x511.jpg" class="card-img-top" alt="Ortodoncia">
            <div class="card-body">
              <h5 class="card-title">Ortodoncia</h5>
              <p class="card-text">Alineamos tus dientes con brackets o invisalign para una sonrisa perfecta y saludable.</p>
              <p class="price">UYU 4.500 por mes</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card service-card shadow-sm">
            <img src="https://cdn1.clinicadentalfabianlopez.com/wp-content/uploads/2015/09/endodoncia-dental.jpg" class="card-img-top" alt="Endodoncia">
            <div class="card-body">
              <h5 class="card-title">Endodoncia</h5>
              <p class="card-text">Tratamiento de conducto para salvar dientes dañados y evitar infecciones graves.</p>
              <p class="price">UYU 8.000 por diente</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card service-card shadow-sm">
            <img src="https://www.dentalnavarro.com/blog/blog/wp-content/uploads/2018/09/faqs-preguntas-implantes-dentales-782x470.png" class="card-img-top" alt="Implantes">
            <div class="card-body">
              <h5 class="card-title">Implantes Dentales</h5>
              <p class="card-text">Reemplaza dientes perdidos con implantes duraderos y funcionales.</p>
              <p class="price">UYU 35.000 por implante</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card service-card shadow-sm">
            <img src="https://www.dentalunzeta.com/es/wp-content/uploads/sites/2/2022/07/shutterstock_364820015-1.jpg" class="card-img-top" alt="Revisiones">
            <div class="card-body">
              <h5 class="card-title">Revisiones Generales</h5>
              <p class="card-text">Chequeos periódicos para mantener la salud dental y prevenir problemas futuros.</p>
              <p class="price">UYU 1.500 por sesión</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

<?php include("includes/footer.php"); ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
