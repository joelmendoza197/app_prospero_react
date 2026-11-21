@component('mail::message')
# ¡Bienvenido a la Familia Próspero!

Hola {{ $user->name }},

Estamos muy emocionados de que hayas decidido tomar el control de las finanzas de **{{ $user->household_name }}**. 

En Próspero creemos que una economía hogareña organizada es la base de la tranquilidad. Para comenzar a utilizar tu dashboard y registrar tus primeros movimientos, solo necesitamos confirmar que esta es tu dirección de correo.

@component('mail::button', ['url' => $url])
Confirmar mi cuenta
@endcomponent

Si no has creado una cuenta en nuestra plataforma, puedes ignorar este correo de forma segura.

Atentamente,<br>
El equipo de **Próspero**
@endcomponent