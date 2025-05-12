<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easter Egg</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
            flex-direction: column;
            background-image: url('img/logo.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            background-blend-mode: lighten;
            background-opacity: 0.2;
            overflow: hidden;
        }
        .easter-egg {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            animation: bounce 1s infinite alternate;
        }
        .emoji {
            font-size: 50px;
            margin-bottom: 20px;
        }
        @keyframes bounce {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-10px);
            }
        }
        .voltar {
            position: relative;
            margin-top: 20px;
            padding: 10px 20px;
            background-color:rgb(55, 60, 157);
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.2s ease;
            z-index: 100;
        }
        .voltar:hover {
            background-color:rgb(0, 30, 255);
        }
        .status-timer {
            margin-top: 10px;
            font-size: 16px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="easter-egg">
        <div class="emoji">🎉</div>
        <p>Não é que você achou o Easter Egg!</p>
        <p>Esse mouse vai quebrar!</p>
    </div>
    <div id="status-timer" class="status-timer">Tente voltar para a home...</div>
    <a href="home.php" class="voltar" id="botao-voltar">Voltar para Home</a>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const botaoVoltar = document.getElementById('botao-voltar');
            const statusTimer = document.getElementById('status-timer');
            let canClick = false;
            let timerCount = 15;
            let timerInterval;
            
            // Desabilita o link inicialmente
            botaoVoltar.addEventListener('click', function(e) {
                if (!canClick) {
                    e.preventDefault();
                }
            });
            
            // Função para mover o botão quando o mouse se aproxima
            function moveButton(e) {
                if (canClick) return;
                
                const button = botaoVoltar;
                const buttonRect = button.getBoundingClientRect();
                const buttonCenterX = buttonRect.left + buttonRect.width / 2;
                const buttonCenterY = buttonRect.top + buttonRect.height / 2;
                
                // Calcula a distância entre o mouse e o centro do botão
                const distX = e.clientX - buttonCenterX;
                const distY = e.clientY - buttonCenterY;
                const distance = Math.sqrt(distX * distX + distY * distY);
                
                // Se o mouse estiver próximo do botão, mova-o para longe
                if (distance < 100) {
                    // Verifica se está próximo aos cantos da tela
                    const isNearLeftEdge = buttonRect.left < 100;
                    const isNearRightEdge = buttonRect.right > window.innerWidth - 100;
                    const isNearTopEdge = buttonRect.top < 100;
                    const isNearBottomEdge = buttonRect.bottom > window.innerHeight - 100;
                    
                    // Se estiver preso em algum canto, faz o botão "pular" para uma posição aleatória
                    if ((isNearLeftEdge && isNearTopEdge) || 
                        (isNearLeftEdge && isNearBottomEdge) || 
                        (isNearRightEdge && isNearTopEdge) || 
                        (isNearRightEdge && isNearBottomEdge)) {
                        
                        // Gera uma posição aleatória na área central da tela
                        const safeZoneSize = 300; // Tamanho da zona segura longe dos cantos
                        const randomX = Math.random() * (window.innerWidth - 2 * safeZoneSize) + safeZoneSize;
                        const randomY = Math.random() * (window.innerHeight - 2 * safeZoneSize) + safeZoneSize;
                        
                        // Aplica a posição aleatória
                        button.style.position = 'absolute';
                        button.style.left = randomX + 'px';
                        button.style.top = randomY + 'px';
                        return;
                    }
                    
                    // Calcula a nova posição com base na direção oposta do mouse
                    let moveX = -distX * 2;
                    let moveY = -distY * 2;
                    
                    // Impede que o botão saia completamente da tela e ajusta a direção se estiver próximo às bordas
                    const maxX = window.innerWidth - buttonRect.width;
                    const maxY = window.innerHeight - buttonRect.height;
                    
                    // Se está próximo de uma borda, força movimento na direção oposta
                    if (isNearLeftEdge) moveX = Math.abs(moveX) + 50;
                    if (isNearRightEdge) moveX = -Math.abs(moveX) - 50;
                    if (isNearTopEdge) moveY = Math.abs(moveY) + 50;
                    if (isNearBottomEdge) moveY = -Math.abs(moveY) - 50;
                    
                    let newX = Math.min(Math.max(buttonRect.left + moveX, 10), maxX - 10);
                    let newY = Math.min(Math.max(buttonRect.top + moveY, 10), maxY - 10);
                    
                    // Aplica a nova posição
                    button.style.position = 'absolute';
                    button.style.left = newX + 'px';
                    button.style.top = newY + 'px';
                }
            }
            
            // Inicia o temporizador
            function startTimer() {
                timerInterval = setInterval(function() {
                    timerCount--;
                    statusTimer.textContent = `O botão tá maluco papai!`;
                    
                    if (timerCount <= 0) {
                        clearInterval(timerInterval);
                        canClick = true;
                        document.removeEventListener('mousemove', moveButton);
                        statusTimer.textContent = 'Fiquei com dó e agora você pode clicar no botão!';
                        botaoVoltar.style.position = 'relative';
                        botaoVoltar.style.left = 'auto';
                        botaoVoltar.style.top = 'auto';
                    }
                }, 1000);
            }
            
            // Adiciona o evento de mousemove
            document.addEventListener('mousemove', moveButton);
            
            // Inicia o temporizador
            startTimer();
        });
    </script>
</body>
</html> 