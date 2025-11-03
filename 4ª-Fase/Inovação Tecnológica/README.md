# 🎮 NextLevel Tech - E-commerce de Periféricos Gamer

Sistema de e-commerce completo para venda de periféricos gamer, desenvolvido em PHP 5.3 com MySQL.

## 🚀 Características

- ✅ **Catálogo de Produtos** com categorias e marcas
- ✅ **Carrinho de Compras** persistente
- ✅ **Sistema de Login** para clientes e administradores
- ✅ **Pagamentos Online** via AbacatePay (PIX)
- ✅ **Gestão de Estoque** automática
- ✅ **Painel Administrativo** completo
- ✅ **Painel Financeiro** (Admin Nível 2)
- ✅ **Design Moderno** e responsivo
- ✅ **Modo de Desenvolvimento** para testes

## 💻 Requisitos

- **PHP** 5.3 ou superior
- **MySQL** 5.5 ou superior
- **Apache** com mod_rewrite
- Extensão **cURL** habilitada
- Extensão **mysqli** habilitada

## 📦 Instalação

### 1. Clone o Repositório

```bash
git clone [url-do-repositorio]
cd NextLevelTech
```

### 2. Importe o Banco de Dados

1. Acesse o phpMyAdmin: `http://localhost/phpmyadmin`
2. Crie um banco chamado `ecommerce_perifericos`
3. Importe o arquivo SQL fornecido (dump completo do banco)

**Nota:** O dump já inclui as tabelas `pedido` e `pedido_item`. O arquivo `database_pedidos_setup.sql` é apenas para referência ou se precisar recriar as tabelas.

### 3. Configure a AbacatePay

**Guia Rápido:**
1. Crie uma conta em https://www.abacatepay.com/
2. Ative o "Dev Mode" 
3. Gere uma chave de API
4. Cole a chave em `loja/abacatepay_config.php`

Documentação completa: **`INSTALACAO_ABACATEPAY.md`**

### 4. Ajuste as URLs

Edite `loja/abacatepay_config.php` e ajuste:

```php
define('SITE_URL', 'http://localhost/seu-caminho/loja');
```

### 5. Acesse o Sistema

- **Loja:** `http://localhost/NextLevelTech/loja/home.php`
- **Admin:** `http://localhost/NextLevelTech/loja/login_adm.php` (apenas localhost)

## 👤 Usuários de Teste

### Cliente
- **Email:** markinhuszanin@gmail.com
- **Senha:** 123456

### Administrador
- **Email:** matheusteste@gmail.com
- **Senha:** 123456

## 📁 Estrutura do Projeto

```
NextLevelTech/
├── loja/                      # Front-end e sistema
│   ├── home.php              # Catálogo de produtos
│   ├── carrinho.php          # Carrinho de compras
│   ├── checkout_abacatepay.php   # Checkout
│   ├── success_abacatepay.php    # Confirmação
│   ├── meus_pedidos.php      # Histórico do cliente
│   ├── financeiro.php        # Painel financeiro (Admin Nível 2) ⭐
│   └── menu.php              # Menu administrativo
├── admin/                     # Gestão de admins
├── produto/                   # Gestão de produtos
├── categoria/                 # Gestão de categorias
└── marca/                     # Gestão de marcas
```

## 🎨 Funcionalidades

### Para Clientes
- 🛍️ Navegar por produtos com filtros
- 🔍 Buscar produtos
- 🛒 Adicionar ao carrinho
- 💳 Pagar com PIX ou Cartão
- 📦 Acompanhar pedidos

### Para Administradores
- ➕ Cadastrar produtos, categorias e marcas
- 📝 Editar produtos e dados
- 🗑️ Excluir produtos
- 📊 Gerenciar estoque
- 👥 Gerenciar administradores (Nível 2)
- 💰 **Painel Financeiro** (Nível 2):
  - Relatórios de vendas
  - Estatísticas de faturamento
  - Produtos mais vendidos
  - Histórico de transações AbacatePay

## 🥑 Sistema de Pagamentos

Este projeto usa a **AbacatePay**, um gateway brasileiro super simples:

- ✅ **PIX** (pagamento instantâneo)
- ✅ **Cartão de Crédito**
- ✅ **Modo de Teste** gratuito
- ✅ **API descomplicada**

### Fluxo de Pagamento

1. Cliente finaliza compra
2. Sistema cria cobrança na AbacatePay
3. Cliente é redirecionado para página de pagamento
4. Cliente paga com PIX ou Cartão
5. Webhook confirma pagamento
6. Estoque é atualizado automaticamente

## 🔧 Desenvolvimento

### Ativar Modo de Desenvolvimento

1. Acesse o dashboard da AbacatePay
2. Ative o **"Dev Mode"**
3. Use a chave de API de teste
4. Todos os pagamentos serão simulados (não reais)

### Simular Pagamento (Modo Dev)

```bash
curl -X POST https://api.abacatepay.com/v1/pix/simulate/BILLING_ID \
  -H "Authorization: Bearer SUA_CHAVE_API"
```

## 🚀 Indo para Produção

1. **Complete a verificação** da conta na AbacatePay
2. **Desative** o Dev Mode
3. **Gere nova chave** de API (produção)
4. **Atualize** `abacatepay_config.php`
5. **Configure webhooks** com URL real
6. **Ative HTTPS** no servidor

## 🔒 Segurança

- ✅ Senhas criptografadas (MD5)
- ✅ Proteção contra SQL Injection
- ✅ Sessões seguras
- ✅ Validação de dados
- ✅ Chaves de API protegidas (gitignore)

## 📚 Documentação

- **AbacatePay:** https://docs.abacatepay.com
- **Instalação Completa:** `INSTALACAO_ABACATEPAY.md`

## 🐛 Suporte

Para problemas relacionados a:
- **Pagamentos:** ajuda@abacatepay.com
- **Sistema:** Consulte o FAQ no `INSTALACAO_ABACATEPAY.md`

## 📝 Licença

Projeto desenvolvido como TCC (Trabalho de Conclusão de Curso).

## 👨‍💻 Desenvolvedores

- Matheus Donadel Marques
- Marcus V

---

**Feito com ❤️ e muito ☕**

