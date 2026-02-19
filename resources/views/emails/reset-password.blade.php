<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña</title>
    <style>
        body { margin:0; padding:0; background:#f0fdf4; font-family: Arial, Helvetica, sans-serif; color:#1f2937; }
        .wrap { max-width: 700px; margin:0 auto; padding:32px 16px; }
        .card { background:#ffffff; border:1px solid #bbf7d0; border-radius:12px; overflow:hidden; box-shadow: 0 8px 24px rgba(16,185,129,0.08); }
        .header { background:#dcfce7; padding:20px 24px; display:flex; align-items:center; gap:16px; }
        .logo { display:flex; align-items:center; justify-content:center; font-size:24px; }
        .title { margin:0; font-size:18px; color:#065f46; font-weight:700; }
        .content { padding:28px 24px; }
        .h1 { margin:0 0 12px; font-size:22px; color:#064e3b; }
        .p { margin:0 0 16px; line-height:1.6; }
        .btn-wrap { margin:24px 0; }
        .btn { background:#22c55e; color:#ffffff !important; text-decoration:none; padding:12px 20px; border-radius:8px; font-weight:600; display:inline-block; box-shadow:0 6px 14px rgba(34,197,94,0.25); }
        .btn:hover { background:#16a34a; }
        .tips { background:#f0fdf4; border:1px solid #bbf7d0; padding:16px; border-radius:10px; }
        .list { padding-left:18px; margin:0; }
        .footer { padding:18px 24px; color:#6b7280; font-size:12px; border-top:1px solid #e5e7eb; }
        .link { color:#065f46; word-break: break-all; }
        .spacer { height:44px; }
    </style>
    <!--
        Reemplaza el contenido del div.logo por la imagen del logo cuando lo tengas disponible:
        <img src="{{ asset('img/logo-compost-cefa.webp') }}" alt="COMPOST CEFA" />
    -->
    
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="header">
                <div class="logo">
                    🌱
                </div>
                <h3 class="title">Soporte de COMPOST CEFA</h3>
            </div>
            <div class="content">
                <h1 class="h1">¡Hola!</h1>
                <p class="p">Somos el equipo de soporte de <strong>COMPOST CEFA</strong> y queremos ayudarte a restablecer tu contraseña.</p>
                <p class="p">Para continuar, haz clic en el siguiente botón:</p>

                <div class="btn-wrap">
                    <a href="{{ $resetUrl }}" class="btn">Restablecer contraseña</a>
                </div>

                <div class="tips">
                    <p class="p" style="margin:0 0 10px 0; font-weight:600; color:#065f46;">Instrucciones rápidas:</p>
                    <ol class="list">
                        <li>Si no solicitaste este cambio, puedes ignorar este correo.</li>
                        <li>El enlace caduca en 60 minutos por seguridad.</li>
                        <li>Si el botón no funciona, copia y pega este enlace en tu navegador:</li>
                    </ol>
                    <p class="p" style="margin-top:8px;"><a class="link" href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
                </div>
                <div class="spacer"></div>
            </div>
            <div class="footer">
                © {{ date('Y') }} COMPOST CEFA — Soporte
            </div>
        </div>
    </div>
</body>
</html>


