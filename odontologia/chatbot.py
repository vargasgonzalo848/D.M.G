from flask import Flask, request, jsonify
from flask_cors import CORS
from pyngrok import ngrok
import re

app = Flask(__name__)
CORS(app)

respuestas = {
    "hola": "¡Mucho gusto, soy tu asistente virtual!. ¿En que puedo ayudarte?",
    "recuperar cuenta": "Para recuperar tu cuenta debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "como recupero mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "y como recupero mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "como puedo recuperar mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "y como puedo recuperar mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "dime como recupero mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "y dime como recupero mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "dime que hacer para recuperar mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "y dime que hacer para recuperar mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "dime donde recupero mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "y dime donde recupero mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "perdi mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "como puedo entrar de nuevo a mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "y como puedo entrar de nuevo a mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "como recupero el acceso a mi cuenta  ": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "y como recupero el acceso a mi cuenta  ": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "como puedo restablecer mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "y como puedo restablecer mi cuenta": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "que hago si no recuerdo mi contraseña": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "y que hago si no recuerdo mi contraseña": "Debes ir a ingresar y luego en olvide mi contraseña debes poner tu correo electronico y recibiras un mensaje de recuperacion en tu gmail.",
    "dime que hacer para pedir turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "quiero saber como pedir un turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "y dime que hacer para pedir turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "dime donde pido turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "y dime donde pido turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "dime como pedir turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "y dime como pedir turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "donde puedo pedir turno": "Buena pregunta. Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "y donde puedo pedir turno": "Buena pregunta. Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "donde pido turno": "Buena pregunta. Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "y donde pido turno": "Buena pregunta. Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "que hago para pedir turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "y que hago para pedir turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "que hago si no tengo turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "y que hago si no tengo turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "pedir turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "como puedo pedir turno": "Buena pregunta. Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "y como puedo pedir turno": "Buena pregunta. Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "quiero pedir turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "quiero pedir un turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "como pido turno": "Buena pregunta. Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "y como pido turno": "Buena pregunta. Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "donde tengo que ir para pedir un turno": "Buena pregunta. Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "quiero turno": "Puedes pedir turnos entrando al botón de mis turnos y luego mas abajo en el boton pedir turnos, y registrar tus datos.",
    "tengo una consulta": "Claro, estoy para ayudar.",
    "consulta": "Claro, estoy para ayudar.",
    "pregunta": "Claro, estoy para ayudar.",
    "tengo una pregunta": "Claro, estoy para ayudar.",
    "como inicio sesion": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "y como inicio sesion": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "donde inicio sesion": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "y donde inicio sesion": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "quiero registrarme": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "y como me registro": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "como me registro": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "quiero crear una cuenta": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "como iniciar sesion": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "donde iniciar sesion": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "y donde puedo registrarme": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "como puedo registrarme": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "y como puedo registrarme": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "donde me registro": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "y donde me registro": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "donde puedo registrarme": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "y donde iniciar sesion": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "inicio sesion": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "iniciar sesion": "Debes ir a ingresar, luego te creas un correo (o con una cuenta existente), pones tus datos y listo, tendras una cuenta.",
    "servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "dime donde puedo encontrar los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "y dime donde puedo encontrar los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "donde encuetro los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "y donde encuetro los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "dime como puedo encontrar los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "y dime como puedo encontrar los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "quiero ver los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "muestrame los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "que hago para ver los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "y que hago para ver los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "como puedo ver los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "y como puedo ver los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "como puedo encontrar los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "y como puedo encontrar los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "donde puedo ver los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "y donde puedo ver los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "donde estan los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "y donde estan los servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "como puedo encontrar servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "y como puedo encontrar servicios": "Podras encontrar mas informacion sobre nuestros servicios en la opcion servicios y encontraras todas las opciones con sus precios.",
    "como puedo comunicarme con el dentista": "Puede ver toda la informacion en la opcion sobre nosotros.",
    "y como puedo comunicarme con el dentista": "Puede ver toda la informacion en la opcion sobre nosotros.",
    "quiero hablar con el dentista": "Puede ver toda la informacion en la opcion sobre nosotros.",
    "hablar con el dentista": "Puede ver toda la informacion en la opcion sobre nosotros.",
    "como puedo hablar con el dentista": "Puede ver toda la informacion en la opcion sobre nosotros.",
    "y como puedo hablar con el dentista": "Puede ver toda la informacion en la opcion sobre nosotros.",
    "donde puedo hablar con el dentista": "Puede ver toda la informacion en la opcion sobre nosotros.",
    "y donde puedo hablar con el dentista": "Puede ver toda la informacion en la opcion sobre nosotros.",
    "quiero comunicarme con el dentista": "Puede ver toda la informacion en la opcion sobre nosotros.",
    "donde puedo comunicarme con el dentista": "Puede ver toda la informacion necesaria en la opcion sobre nosotros.",
    "y donde puedo comunicarme con el dentista": "Puede ver toda la informacion necesaria en la opcion sobre nosotros.",
    "comunicarme con el dentista": "Puede ver toda la informacion en la opcion sobre nosotros.",
    "gracias": "¡No hay problema, es un gusto poder ayudarte!",
    "muchas gracias": "¡No hay problema, es un gusto poder ayudarte!",
    "te agradezco": "¡No hay problema, es un gusto poder ayudarte!",
    "configuraciones": "Puedes ver las opciones o configuraciones entrando en la opcion que dice perfil en tu cuenta.",
    "donde puedo ver las configuraciones": "Puedes ver las configuraciones entrando en la opcion que dice perfil en tu cuenta.",
    "quiero ver las configuraciones": "Puedes ver las configuraciones entrando en la opcion que dice perfil en tu cuenta.",
    "y las configuraciones": "Puedes ver las configuraciones entrando en la opcion que dice perfil en tu cuenta.",
    "adios": "¡Hasta luego! Fue un gusto hablar contigo.",
    "hasta luego": "¡Hasta luego! Fue un gusto hablar contigo.",
}

def responder_diccionario(mensaje):
    mensaje = mensaje.lower().strip()
    
    if mensaje in respuestas:
        return respuestas[mensaje]
    
    for clave in respuestas:
        if clave in mensaje:
            return respuestas[clave]
    
    return "No entendí eso, ¿puede decirlo de otra manera?"

def obtener_respuesta_adicional(mensaje):
    mensaje = mensaje.lower().strip()
    
    if any(palabra in mensaje for palabra in ['horario', 'hora', 'abierto', 'abre', 'cierra', 'atienden', 'atención']):
        return """⏰ Nuestros horarios de atención:

• Lunes a Viernes: 08:00 AM - 09:00 PM
• Sábado: 08:00 AM - 04:00 PM  
• Domingo: Cerrado

¿Necesitas agendar una cita?"""
    
    if 'limpieza' in mensaje and any(palabra in mensaje for palabra in ['precio', 'costo', 'cuanto', 'cuánto', 'vale', 'cobran']):
        return "💰 La limpieza dental cuesta UYU 2.500 por sesión. Incluye remoción de placa, sarro y pulido dental. ¿Te gustaría agendar una cita?"
    
    if 'blanqueamiento' in mensaje and any(palabra in mensaje for palabra in ['precio', 'costo', 'cuanto', 'cuánto', 'vale', 'cobran']):
        return "💰 El blanqueamiento dental cuesta UYU 6.000 por sesión. Recupera el color natural de tus dientes en una sola visita. ¿Quieres más información?"
    
    if ('ortodoncia' in mensaje or 'brackets' in mensaje or 'frenillos' in mensaje) and any(palabra in mensaje for palabra in ['precio', 'costo', 'cuanto', 'cuánto', 'vale', 'cobran']):
        return "💰 El tratamiento de ortodoncia cuesta UYU 4.500 por mes. Incluye brackets tradicionales o invisalign. El tiempo de tratamiento varía según cada caso. ¿Te gustaría una evaluación?"
    
    if ('endodoncia' in mensaje or 'conducto' in mensaje) and any(palabra in mensaje for palabra in ['precio', 'costo', 'cuanto', 'cuánto', 'vale', 'cobran']):
        return "💰 La endodoncia (tratamiento de conducto) cuesta UYU 8.000 por diente. Este tratamiento salva dientes dañados y elimina infecciones. ¿Tienes molestias dentales?"
    
    if 'implante' in mensaje and any(palabra in mensaje for palabra in ['precio', 'costo', 'cuanto', 'cuánto', 'vale', 'cobran']):
        return "💰 Los implantes dentales cuestan UYU 35.000 por implante. Son la mejor solución para reemplazar dientes perdidos de forma permanente. ¿Quieres una evaluación?"
    
    if any(palabra in mensaje for palabra in ['precio', 'precios', 'costo', 'costos', 'tarifa']) and 'todos' in mensaje or 'cuales' in mensaje or 'lista' in mensaje:
        return """💰 Nuestros precios:

• Limpieza Dental: UYU 2.500 por sesión
• Blanqueamiento: UYU 6.000 por sesión
• Ortodoncia: UYU 4.500 por mes
• Endodoncia: UYU 8.000 por diente
• Implantes Dentales: UYU 35.000 por implante
• Revisiones Generales: UYU 1.500 por sesión

¿Sobre qué servicio quieres más información?"""
    
    if any(palabra in mensaje for palabra in ['ubicacion', 'ubicación', 'direccion', 'dirección', 'donde quedan', 'dónde quedan', 'donde están', 'encuentran']):
        return """📍 Nuestra ubicación:

Calle Gualavi 34, Salto, Uruguay
📞 Teléfono: +598 092 434 321
📧 Email: mejorodontologia@gmail.com

Puedes ver el mapa en nuestra página principal."""
    
    if any(palabra in mensaje for palabra in ['emergencia', 'urgencia', 'dolor', 'duele', 'urgente']):
        return """🚨 Para emergencias dentales:

Llámanos inmediatamente al: +598 092 434 321

Atendemos emergencias de lunes a sábado. Si es fuera de nuestro horario, te recomendamos acudir al servicio de urgencias más cercano."""
    
    if any(palabra in mensaje for palabra in ['pago', 'pagar', 'efectivo', 'tarjeta', 'débito', 'crédito', 'financiación', 'cuotas']):
        return """💳 Formas de pago:

Aceptamos:
• Efectivo
• Tarjetas de débito y crédito
• Transferencias bancarias
• Planes de financiación (consultar disponibilidad)

Para más detalles sobre financiación, comunícate al +598 092 434 321"""
    
    return None


@app.route('/', methods=['POST'])
@app.route('/chatbot', methods=['POST'])
def chatbot():
    try:
        data = request.json
        mensaje = data.get('mensaje', '').strip()
        
        if not mensaje:
            return jsonify({'respuesta': 'Por favor, escribe un mensaje.'}), 400
        
        respuesta = responder_diccionario(mensaje)
        
        if respuesta == "No entendí eso, ¿puede decirlo de otra manera?":
            respuesta_adicional = obtener_respuesta_adicional(mensaje)
            if respuesta_adicional:
                respuesta = respuesta_adicional
        
        return jsonify({'respuesta': respuesta})
    
    except Exception as e:
        print(f"Error: {str(e)}")
        return jsonify({
            'respuesta': 'Lo siento, ocurrió un error. Por favor, intenta nuevamente.'
        }), 500


@app.route('/health', methods=['GET'])
def health():
    return jsonify({'status': 'ok', 'message': 'Chatbot Odontología Fanny funcionando'})


if __name__ == '__main__':
    try:
        public_url = ngrok.connect(5000)
        print("=" * 60)
        print("🦷 Chatbot Odontología Fanny - INICIADO")
        print("=" * 60)
        print(f"📡 URL local:   http://localhost:5000")
        print(f"🌐 URL pública: {public_url}")
        print("=" * 60)
        print("✅ Presiona Ctrl+C para detener el servidor")
        print("=" * 60)
    except Exception as e:
        print(f"⚠️  No se pudo iniciar ngrok: {e}")
        print("📡 Servidor corriendo solo en: http://localhost:5000")
    
    app.run(host='0.0.0.0', port=5000, debug=True)
