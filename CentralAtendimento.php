<?php
session_start();

// Verificar se o usuário está logado, exceto se veio da página de login
if (!isset($_SESSION['nome_usuario']) && !isset($_GET['origem'])) {
    header('Location: Login/login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central de Ajuda</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        .help-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .help-title {
            text-align: center;
            color: #3b82f6;
            margin-bottom: 30px;
            font-size: 2.2rem;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 15px;
        }
        
        /* Estilo do acordeão */
        .accordion {
            margin-bottom: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .accordion-header {
            background-color: #3b82f6;
            color: white;
            padding: 15px 20px;
            cursor: pointer;
            position: relative;
            font-weight: 500;
        }
        
        .accordion-header:hover {
            background-color: #2563eb;
        }
        
        .accordion-header::after {
            content: '+';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
        }
        
        .accordion-header.active::after {
            content: '-';
        }
        
        .accordion-content {
            background: #f9fafb;
            display: none;
            padding: 20px;
        }
        
        .accordion-content p {
            margin-top: 0;
            margin-bottom: 15px;
        }
        
        .accordion-content ul, 
        .accordion-content ol {
            margin-left: 25px;
            margin-top: 10px;
        }
        
        .accordion-content li {
            margin-bottom: 10px;
        }
        
        .back-button {
            text-align: center;
            margin-top: 30px;
        }
        
        .btn-back {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            font-weight: 500;
        }
        
        .btn-back:hover {
            background-color: #2563eb;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            color: #9ca3af;
            font-size: 0.9rem;
        }
        
        /* Estilo para o ponto de interrogação que foge do mouse */
        .help-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #3b82f6;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            font-size: 20px;
            text-decoration: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="help-container">
        <h1 class="help-title">FAQ: Respostas para as suas dúvidas sobre o sistemaJFS</h1>
        
        <!-- Login Section -->
        <div class="accordion">
            <div class="accordion-header">Problemas com login</div>
            <div class="accordion-content">
                <p>Para acessar o sistema, utilize seu login e a senha fornecidos pelo suporte.</p>
                <p>Se esqueceu seu login ou senha, entre em contato com o suporte.</p>
                <p>Telefone: (18) 99800-5326 ou (18) 99826-1271</p>
            </div>
        </div>
        
        <!-- Equipment Management -->
        <div class="accordion">
            <div class="accordion-header">Como cadastrar novos equipamentos</div>
            <div class="accordion-content">
                <p>Acesse o menu "Cadastro de Equipamentos" e preencha todos os campos obrigatórios:</p>
                <ul>
                    <li>Tipo de equipamento (Notebook / Tablets)</li>
                    <li>Número Patrimonio (Número colado no Notebook)</li>
                    <li>Data de Aquisição (Quando o notebook foi recebido)</li>
                </ul>
            </div>
        </div>
        
        <div class="accordion">
            <div class="accordion-header">Registro de retirada de equipamentos</div>
            <div class="accordion-content">
                <p>Para registrar a retirada de um equipamento:</p>
                <ol>
                    <li>Acesse a aba "Retirada/Devolução"</li>
                    <li>Defina a data e o horario de retirada</li>
                    <li>Informe o professor responsável</li>
                    <li>Informe a sala</li>
                    <li>Selecione os equipamentos</li>
                </ol>
            </div>
        </div>
        
        <!-- Maintenance Section -->
        <div class="accordion">
            <div class="accordion-header">Como registrar manutenção</div>
            <div class="accordion-content">
                <p>Para registrar uma manutenção:</p>
                <ol>
                    <li>Acesse a aba "Equipamentos"</li>
                    <li>Clique na opção "Equipamentos Cadastrados"</li>
                    <li>Clique em "Editar" no equipamento desejado</li>
                    <li>E mude o status para em manutenção</li>
                    <li>Clique em "Salvar"</li>
                </ol>
            </div>
        </div>
        
        <div class="accordion">
            <div class="accordion-header">Consultar histórico de manutenções</div>
            <div class="accordion-content">
                <p>O histórico completo de manutenções pode ser acessado no menu "Histórico de Manutenções".</p>
                <p>Você pode filtrar por número de patrimônio ou período.</p>
            </div>
        </div>
        
        <!-- Professor Management -->
        <div class="accordion">
            <div class="accordion-header">Cadastro de professores</div>
            <div class="accordion-content">
                <p>Para cadastrar um novo professor:</p>
                <ol>
                    <li>Acesse "Cadastro de Professores"</li>
                    <li>Preencha com o nome do professor</li>
                    <li>Clique em "Cadastrar"</li>
                </ol>
            </div>
        </div>
        
        <!-- General Help -->
        <div class="accordion">
            <div class="accordion-header">Como entrar em contato com o suporte do sistema</div>
            <div class="accordion-content">
                <p>Para suporte técnico, entre em contato:</p>
                <ul>
                 <li>Telefone: (18) 99800-5326 ou (18) 99826-1271</li>
                </ul>
            </div>
        </div>
        
        <div class="accordion">
            <div class="accordion-header">Como editar informações cadastradas</div>
            <div class="accordion-content">
                <p>Para editar qualquer informação:</p>
                <ol>
                    <li>Acesse o menu correspondente (Equipamentos, Professores, etc.)</li>
                    <li>Clique no formulário correspondente (Equipamentos, Professores, etc.)</li>
                    <li>Faça as alterações necessárias e salve</li>
                </ol>
            </div>
        </div>
        
        <div class="back-button">
            <?php if(isset($_GET['origem']) && $_GET['origem'] == 'login'): ?>
                <a href="Login/login.html" class="btn-back">Voltar</a>
            <?php else: ?>
                <a href="home.php" class="btn-back">Voltar</a>
            <?php endif; ?>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 10px; margin-top: 20px; opacity: 0.3;">
        <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
    </footer>

    <a href="#" class="help-button" id="fleeingQuestion">?</a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleciona todos os cabeçalhos do acordeão
            var accordionHeaders = document.querySelectorAll('.accordion-header');
            
            // Adiciona evento de clique para cada cabeçalho
            accordionHeaders.forEach(function(header) {
                header.addEventListener('click', function() {
                    // Toggle da classe 'active' no cabeçalho clicado
                    this.classList.toggle('active');
                    
                    // Pega o próximo elemento (que é o conteúdo do acordeão)
                    var content = this.nextElementSibling;
                    
                    // Alterna a exibição do conteúdo
                    if (content.style.display === 'block') {
                        content.style.display = 'none';
                    } else {
                        content.style.display = 'block';
                    }
                });
            });
            
            // Easter egg: ponto de interrogação que foge do mouse
            const questionMark = document.getElementById('fleeingQuestion');
            
            document.addEventListener('mousemove', function(event) {
                const mouseX = event.clientX;
                const mouseY = event.clientY;
                
                const markRect = questionMark.getBoundingClientRect();
                const markX = markRect.left + markRect.width / 2;
                const markY = markRect.top + markRect.height / 2;
                
                // Calcular a distância entre o mouse e o ponto de interrogação
                const distance = Math.sqrt(Math.pow(mouseX - markX, 2) + Math.pow(mouseY - markY, 2));
                
                // Se o mouse estiver próximo, mova o ponto de interrogação para longe
                if (distance < 100) {
                    // Calcular a direção oposta
                    const directionX = markX - mouseX;
                    const directionY = markY - mouseY;
                    
                    // Normalizar a direção
                    const length = Math.sqrt(directionX * directionX + directionY * directionY);
                    const normalizedX = directionX / length;
                    const normalizedY = directionY / length;
                    
                    // Mover o ponto de interrogação
                    const moveDistance = Math.max(0, (100 - distance) / 2);
                    const newBottom = parseInt(window.getComputedStyle(questionMark).bottom) + normalizedY * moveDistance;
                    const newRight = parseInt(window.getComputedStyle(questionMark).right) + normalizedX * moveDistance;
                    
                    // Garantir que o ponto de interrogação fique na tela
                    const windowHeight = window.innerHeight;
                    const windowWidth = window.innerWidth;
                    
                    if (newBottom > 20 && newBottom < windowHeight - 60) {
                        questionMark.style.bottom = newBottom + 'px';
                    }
                    
                    if (newRight > 20 && newRight < windowWidth - 60) {
                        questionMark.style.right = newRight + 'px';
                    }
                }
            });
        });
    </script>
</body>
</html>
