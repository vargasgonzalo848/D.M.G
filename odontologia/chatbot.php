<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chatbot Integrado</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        #chatbox { background: #fff; padding: 10px; border-radius: 10px; width: 400px; }
        .msg { margin: 5px 0; }
        .user { color: blue; }
        .bot { color: green; }
    </style>
</head>
<body>
    <h2>Chatbot Integrado (Python + PHP)</h2>
    <div id="chatbox"></div>
    <input type="text" id="mensaje" placeholder="Escribe tu mensaje..." />
    <button onclick="enviarMensaje()">Enviar</button>

    <script>
        async function enviarMensaje() {
            const mensaje = document.getElementById("mensaje").value;
            document.getElementById("chatbox").innerHTML += `<div class="msg user">👤: ${mensaje}</div>`;
            
            const respuesta = await fetch("https://protrudable-supervirulently-rosalba.ngrok-free.dev", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ mensaje: mensaje })
            });
            
            const data = await respuesta.json();
            document.getElementById("chatbox").innerHTML += `<div class="msg bot">🤖: ${data.respuesta}</div>`;
            document.getElementById("mensaje").value = "";
        }
    </script>
</body>
</html>
