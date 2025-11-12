from flask import Flask, request, jsonify
from flask_cors import CORS
import re

app = Flask(__name__)
CORS(app)

def obtener_respuesta(mensaje):
    """
    Procesa el mensaje del usuario y devuelve una respuesta apropiada
    """
    mensaje = mensaje.lower().strip()
    
    if any(palabra in mensaje for palabra in ['hola', 'buenos', 'buenas', 'hey', 'saludos']):
        return "¡Hola! 👋 Bienvenido a Odontología Fanny. Estoy aquí para ayudarte con información sobre nuestros servicios, horarios, precios y citas. ¿En qué puedo asistirte?"
    
    if any(palabra in mensaje for palabra in ['horario', 'hora', 'abierto', 'abre', 'cierra', 'atienden', 'atención']):
        return """⏰ **Nuestros horarios de atención:**

• Lunes a Viernes: 08:00 AM - 09:00 PM
• Sábado: 08:00 AM - 04:00 PM  
• Domingo: Cerrado

¿Necesitas agendar una cita?"""
    
    if 'limpieza' in mensaje and any(palabra in mensaje for palabra in ['precio', 'costo', 'cuanto', 'vale', 'cobran']):
        return "💰 La limpieza dental cuesta **UYU 2.500** por sesión. Incluye remoción de placa, sarro y pulido dental. ¿Te gustaría agendar una cita?"
    
    if 'blanqueamiento' in mensaje and any(palabra in mensaje for palabra in ['precio', 'costo', 'cuanto', 'vale', 'cobran']):
        return "💰 El blanqueamiento dental cuesta **UYU 6.000** por sesión. Recupera el color natural de tus dientes en una sola visita. ¿Quieres más información?"
    
    if 'ortodoncia' in mensaje or 'brackets' in mensaje or 'frenillos' in mensaje:
        if any(palabra in mensaje for palabra in ['precio', 'costo', 'cuanto', 'vale', 'cobran']):
            return "💰 El tratamiento de ortodoncia cuesta **UYU 4.500 por mes**. Incluye brackets tradicionales o invisalign. El tiempo de tratamiento varía según cada caso. ¿Te gustaría una evaluación?"
        return "🦷 Ofrecemos ortodoncia con brackets tradicionales e invisalign para alinear tus dientes y mejorar tu sonrisa. El costo es de UYU 4.500/mes. ¿Quieres agendar una consulta?"
    
    if 'endodoncia' in mensaje or 'conducto' in mensaje:
        if any(palabra in mensaje for palabra in ['precio', 'costo', 'cuanto', 'vale', 'cobran']):
            return "💰 La endodoncia (tratamiento de conducto) cuesta **UYU 8.000 por diente**. Este tratamiento salva dientes dañados y elimina infecciones. ¿Tienes molestias dentales?"
        return "🦷 La endodoncia es un tratamiento de conducto para salvar dientes dañados y evitar infecciones. Costo: UYU 8.000 por diente. ¿Necesitas más información?"
    
    if 'implante' in mensaje:
        if any(palabra in mensaje for palabra in ['precio', 'costo', 'cuanto', 'vale', 'cobran']):
            return "💰 Los implantes dentales cuestan **UYU 35.000 por implante**. Son la mejor solución para reemplazar dientes perdidos de forma permanente y funcional. ¿Quieres una evaluación?"
        return "🦷 Los implantes dentales son la mejor opción para reemplazar dientes perdidos. Son duraderos, funcionales y lucen naturales. Costo: UYU 35.000 por implante. ¿Te interesa?"
    
    if any(palabra in mensaje for palabra in ['precio', 'precios', 'costo', 'costos', 'cuanto', 'vale', 'cobran', 'tarifa']):
        return """💰 **Nuestros precios:**

• Limpieza Dental: UYU 2.500 por sesión
• Blanqueamiento: UYU 6.000 por sesión
• Ortodoncia: UYU 4.500 por mes
• Endodoncia: UYU 8.000 por diente
• Implantes Dentales: UYU 35.000 por implante
• Revisiones Generales: UYU 1.500 por sesión

¿Sobre qué servicio quieres más información?"""
    
    if any(palabra in mensaje for palabra in ['turno', 'cita', 'reserva', 'agendar', 'reservar', 'pedir', 'solicitar', 'appointment']):
        return """📅 **Para solicitar un turno puedes:**

1. Hacer clic en cualquier servicio de nuestra página web
2. Llamarnos al: **+598 092 434 321**
3. Enviarnos un email: **mejorodontologia@gmail.com**

Nuestro equipo te contactará para confirmar tu cita. ¿En qué horario prefieres?"""
    
    if any(palabra in mensaje for palabra in ['ubicacion', 'ubicación', 'direccion', 'dirección', 'donde', 'dónde', 'quedan', 'encuentran', 'direccion']):
        return """📍 **Nuestra ubicación:**

Calle Gualavi 34, Salto, Uruguay
📞 Teléfono: +598 092 434 321
📧 Email: mejorodontologia@gmail.com

Puedes ver el mapa en nuestra página principal. ¿Necesitas indicaciones específicas?"""
    
    if any(palabra in mensaje for palabra in ['servicio', 'servicios', 'ofrecen', 'hacen', 'tratamiento']):
        return """🦷 **Nuestros servicios incluyen:**

• Limpieza Dental (UYU 2.500)
• Blanqueamiento Dental (UYU 6.000)
• Ortodoncia/Brackets (UYU 4.500/mes)
• Endodoncia/Conductos (UYU 8.000)
• Implantes Dentales (UYU 35.000)
• Revisiones Generales (UYU 1.500)

¿Sobre cuál te gustaría saber más?"""
    
    if any(palabra in mensaje for palabra in ['emergencia', 'urgencia', 'dolor', 'duele', 'urgente', 'ahora']):
        return """🚨 **Para emergencias dentales:**

Llámanos inmediatamente al: **+598 092 434 321**

Atendemos emergencias de lunes a sábado. Si es fuera de nuestro horario, te recomendamos acudir al servicio de urgencias más cercano. ¿Qué tipo de molestia tienes?"""
    
    if any(palabra in mensaje for palabra in ['pago', 'pagar', 'efectivo', 'tarjeta', 'débito', 'crédito', 'financiación', 'cuotas']):
        return """💳 **Formas de pago:**

Aceptamos:
• Efectivo
• Tarjetas de débito y crédito
• Transferencias bancarias
• Planes de financiación (consultar disponibilidad)

Para más detalles sobre financiación, comunícate con nosotros al +598 092 434 321"""
    
    if any(palabra in mensaje for palabra in ['gracias', 'chau', 'adiós', 'adios', 'bye', 'hasta luego']):
        return "¡De nada! 😊 Fue un placer ayudarte. Si tienes más preguntas, estoy aquí. ¡Cuida tu sonrisa! 🦷✨"
    
    return """Gracias por tu mensaje. Puedo ayudarte con información sobre:

🦷 **Servicios** (limpieza, blanqueamiento, ortodoncia, implantes)
💰 **Precios** y formas de pago
⏰ **Horarios** de atención
📍 **Ubicación** de la clínica
📅 **Citas y turnos**

¿Qué te gustaría saber?"""


@app.route('/', methods=['POST'])
def chatbot():
    """
    Endpoint principal del chatbot
    """
    try:
        data = request.json
        mensaje = data.get('mensaje', '').strip()
        
        if not mensaje:
            return jsonify({'respuesta': 'Por favor, escribe un mensaje.'}), 400
        
        respuesta = obtener_respuesta(mensaje)
        
        return jsonify({'respuesta': respuesta})
    
    except Exception as e:
        print(f"Error: {str(e)}")
        return jsonify({
            'respuesta': 'Lo siento, ocurrió un error al procesar tu mensaje. Por favor, intenta nuevamente.'
        }), 500


@app.route('/health', methods=['GET'])
def health():
    """
    Endpoint para verificar que el servidor está funcionando
    """
    return jsonify({'status': 'ok', 'message': 'Chatbot funcionando correctamente'})


if __name__ == '__main__':
    print("🦷 Iniciando chatbot de Odontología Fanny...")
    print("📡 Servidor corriendo en: http://localhost:5000")
    print("✅ Presiona Ctrl+C para detener el servidor")
    app.run(host='0.0.0.0', port=5000, debug=True)