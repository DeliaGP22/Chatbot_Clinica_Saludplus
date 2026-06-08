# 🏥 Chatbot Clínica SaludPlus

Sistema web desarrollado en PHP y MySQL para la gestión de citas médicas.

## 📋 Descripción

Este proyecto permite a los usuarios reservar citas médicas de forma sencilla mediante una interfaz web. Además, incorpora un panel de administración para gestionar las citas registradas y un sistema de envío de correos electrónicos de confirmación utilizando PHPMailer.

## ✨ Funcionalidades

- Reserva de citas médicas.
- Almacenamiento de citas en MySQL.
- Inicio de sesión para administrador.
- Panel de administración.
- Gestión de pacientes y citas.
- Envío de correos de confirmación mediante PHPMailer.
- Interfaz sencilla y responsive.

## 🛠️ Tecnologías utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- PHPMailer
- XAMPP

## 🚀 Instalación

1. Clonar el repositorio:

```bash
git clone https://github.com/DeliaGP22/Chatbot-Clinica-Saludplus.git
```

2. Copiar la carpeta del proyecto en `htdocs`.

3. Iniciar Apache y MySQL desde XAMPP.

4. Importar la base de datos:

- Abrir phpMyAdmin.
- Crear una base de datos llamada:

```
clinica_citas
```

- Importar el archivo:

```
clinica_citas.sql
```

5. Acceder a la aplicación:

```
http://localhost/chatbot-clinica/
```

## 📧 Configuración de correo

Por motivos de seguridad, las credenciales SMTP no se incluyen en este repositorio.

Para habilitar el envío de correos es necesario configurar las credenciales de Gmail en:

```
email.php
```

## 👩‍💻 Autor

**Delia Gallardo Pastor**

Proyecto desarrollado como práctica de Desarrollo de Aplicaciones Multiplataforma (DAM).
