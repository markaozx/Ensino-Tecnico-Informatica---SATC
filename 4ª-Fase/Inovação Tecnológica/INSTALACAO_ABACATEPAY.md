# 🥑 Instalação do Sistema de Pagamento AbacatePay

## 📋 O que é AbacatePay?

A **AbacatePay** é um gateway de pagamento brasileiro **super simples** que suporta:
- ✅ **PIX** (instantâneo)
- ✅ **Cartão de Crédito**
- ✅ **Modo de Desenvolvimento** gratuito para testes
- ✅ **API descomplicada** (muito mais fácil que Stripe!)

Documentação oficial: https://docs.abacatepay.com/pages/introduction

## 🚀 Instalação Rápida

### Passo 1: Criar Conta na AbacatePay

1. Acesse: https://www.abacatepay.com/
2. Clique em **"Criar Conta"**
3. Preencha seus dados
4. Faça login no **Dashboard**: https://www.abacatepay.com/

### Passo 2: Ativar Modo de Desenvolvimento

1. No dashboard, você verá no canto superior direito um botão **"Dev Mode"**
2. Clique para ativar o **modo de desenvolvimento**
3. Neste modo você pode testar **sem cobrar de verdade**!

### Passo 3: Obter Chave de API

1. No dashboard, vá em **"Integrar"** (menu lateral)
2. Clique em **"Criar"** para gerar uma nova chave
3. Dê um nome para identificar (ex: "Loja NextLevel")
4. **COPIE A CHAVE** gerada (ela aparece apenas uma vez!)

### Passo 4: Configurar no Projeto

1. Abra o arquivo: `loja/abacatepay_config.php`
2. Cole sua chave de API:

```php
define('ABACATEPAY_API_KEY', 'COLE_SUA_CHAVE_AQUI');
```

3. Se necessário, ajuste a URL do site:

```php
define('SITE_URL', 'http://localhost/NextLevelTech%20-%20Projeto%20TCC/loja');
```

### Passo 5: Criar Tabelas no Banco de Dados

Execute o script SQL no phpMyAdmin (SE AS TABELAS NÃO EXISTIREM):

1. Acesse: `http://localhost/phpmyadmin`
2. Selecione o banco `ecommerce_perifericos`
3. Clique na aba **SQL**
4. Cole e execute o conteúdo do arquivo `database_pedidos_setup.sql`

**Nota:** Se você já importou o dump completo do banco, as tabelas `pedido` e `pedido_item` já existem. Pule este passo!

## 🎯 Como Testar

### 1. Faça Login como Cliente

Use o usuário de teste existente:
- **Email:** markinhuszanin@gmail.com
- **Senha:** 123456

### 2. Adicione Produtos ao Carrinho

- Navegue pela loja
- Adicione produtos ao carrinho

### 3. Finalize a Compra

- Clique no botão **"🥑 Finalizar Compra (PIX ou Cartão)"**
- Você será redirecionado para a página de pagamento da AbacatePay
- No **modo de desenvolvimento**, você pode escolher:
  - **Pagar com PIX** (gera QR Code de teste)
  - **Pagar com Cartão** (não cobra de verdade!)

### 4. Simular Pagamento (Modo Dev)

Como você está no modo de desenvolvimento, o pagamento **NÃO É REAL**.

Para simular que o pagamento foi feito, use a API da AbacatePay:

```bash
# Pegue o ID da cobrança (billing_id) no banco de dados
# Tabela: pedido, campo: stripe_session_id

curl -X POST https://api.abacatepay.com/v1/pix/simulate/SEU_BILLING_ID \
  -H "Authorization: Bearer SUA_CHAVE_API" \
  -H "Content-Type: application/json"
```

Ou aguarde alguns segundos e recarregue a página de sucesso.

## 📂 Arquivos Criados

### Arquivos Principais
- `loja/abacatepay_config.php` - Configuração da API
- `loja/checkout_abacatepay.php` - Processa checkout e cria cobrança
- `loja/success_abacatepay.php` - Página de confirmação
- `loja/webhook_abacatepay.php` - Recebe notificações de pagamento
- `loja/meus_pedidos.php` - Lista pedidos do cliente

### Arquivos Modificados
- `loja/carrinho.php` - Botão "Finalizar Compra" com AbacatePay

## 🔔 Configurar Webhooks (Opcional)

Os webhooks permitem que a AbacatePay notifique seu sistema quando um pagamento é confirmado.

1. No dashboard da AbacatePay, vá em **"Webhooks"**
2. Clique em **"Adicionar Webhook"**
3. Configure:
   - **URL**: `https://seusite.com.br/loja/webhook_abacatepay.php`
   - **Eventos**: Selecione "Pagamento Confirmado"
4. Salve

**Para testes locais**, use **ngrok** para expor seu localhost:
```bash
ngrok http 80
```

## 🎨 Fluxo de Pagamento

```
1. Cliente no Carrinho
   ↓
2. Clica em "Finalizar Compra"
   ↓
3. checkout_abacatepay.php:
   - Cria pedido no banco
   - Cria cobrança na AbacatePay
   - Redireciona para página de pagamento
   ↓
4. Cliente paga (PIX ou Cartão)
   ↓
5. AbacatePay confirma pagamento
   ↓
6. Webhook atualiza status do pedido
   ↓
7. Cliente vê página de sucesso
```

## 💰 Indo para Produção

Quando estiver pronto para aceitar pagamentos reais:

### 1. Verificação de Conta

1. No dashboard, **desative o Dev Mode**
2. Complete o processo de verificação da sua conta
3. Envie os documentos solicitados

Documentação: https://docs.abacatepay.com/pages/production

### 2. Gerar Chave de Produção

1. Com o Dev Mode **desativado**, vá em "Integrar"
2. Crie uma nova chave de API (agora será de produção)
3. Atualize no `abacatepay_config.php`

### 3. Configurar Webhooks de Produção

- Use a URL real do seu site (não localhost)
- Configure os mesmos eventos

## 🔒 Segurança

- ❌ **NUNCA** compartilhe sua chave de API
- ❌ **NUNCA** comite a chave no Git
- ✅ Use `.gitignore` para excluir `abacatepay_config.php`
- ✅ Use HTTPS em produção

## 📊 Estrutura do Banco

As tabelas são as mesmas criadas anteriormente:
- `pedido` - Armazena pedidos
- `pedido_item` - Itens de cada pedido

O campo `stripe_session_id` é reaproveitado para armazenar o `billing_id` da AbacatePay.

## 🐛 Troubleshooting

### Erro: "cURL error"
- Habilite a extensão cURL no php.ini
- Reinicie o servidor web

### Erro: "Unauthorized" (401)
- Verifique se a chave de API está correta
- Confirme que está no Dev Mode se usar chave de teste

### Pagamento não confirma
- Verifique se o webhook está configurado
- Veja o arquivo `loja/abacatepay_webhook_log.txt` para logs
- No modo dev, use o endpoint `/pix/simulate` para simular pagamento

### Tabelas não existem
- Execute o script `database_stripe_setup.sql`
- Verifique as permissões do usuário MySQL

## 🎯 Vantagens da AbacatePay

✅ **Muito mais simples** que Stripe  
✅ **Brasileiro** (suporte em PT-BR)  
✅ **PIX integrado** (pagamento instantâneo)  
✅ **Modo dev grátis** para testes ilimitados  
✅ **API descomplicada** (3 linhas de código!)  
✅ **Sem burocracia** de homologação  

## 📧 Suporte

- **Email:** ajuda@abacatepay.com
- **Documentação:** https://docs.abacatepay.com
- **Dashboard:** https://www.abacatepay.com

## ✅ Checklist Final

- [ ] Conta criada na AbacatePay
- [ ] Dev Mode ativado
- [ ] Chave de API gerada e configurada
- [ ] Tabelas criadas no banco de dados
- [ ] Testado fluxo de pagamento
- [ ] Webhooks configurados (produção)
- [ ] Backup do banco de dados

---

**Pronto! 🎉** Seu sistema de pagamentos com AbacatePay está funcionando!

A implementação é **muito mais simples** que Stripe e totalmente adequada ao mercado brasileiro! 🇧🇷🥑

