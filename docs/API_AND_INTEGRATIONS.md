# Integrações & Gateways de Pagamento — ZamEdu

Este documento descreve as integrações de gateways financeiros, webhooks e APIs REST do **ZamEdu**.

---

## 💳 1. Gateways de Pagamento Moçambicanos

O ZamEdu inclui suporte nativo e tratamento de webhooks para os 3 principais métodos de pagamento em Moçambique:

```
[Cliente / Encarregado]
         |
         v
+------------------+
| Portal dos Pais  |
+--------+---------+
         |
         +-------------------+-------------------+
         |                   |                   |
         v                   v                   v
 +---------------+   +---------------+   +---------------+
 |  M-Pesa API   |   |   eMola API   |   | Multicaixa API|
 +-------+-------+   +-------+-------+   +-------+-------+
         |                   |                   |
         v                   v                   v
 +-------------------------------------------------------+
 |               Webhooks (`/webhooks/*`)               |
 +---------------------------+---------------------------+
                             |
                             v
                 +-----------------------+
                 | Liquidação Automática |
                 | do Estado de Payment  |
                 +-----------------------+
```

---

## 📲 2. Endpoints de Webhooks Nativos

- **M-Pesa**: `POST /webhooks/mpesa` (`PaymentController@webhookMpesa`)
- **eMola**: `POST /webhooks/emola` (`PaymentController@webhookEmola`)
- **Multicaixa**: `POST /webhooks/multicaixa` (`PaymentController@webhookMulticaixa`)

### Exemplo de Payload de Webhook Recebido:

```json
{
  "transaction_id": "MP202607310820",
  "payment_reference": "ZAM-MENS-179-02-2026-283",
  "amount": 2530.00,
  "status": "SUCCESS",
  "phone_number": "258841234567"
}
```

---

## 🔒 3. Autenticação REST & Sanctum

A aplicação disponibiliza autenticação baseada em tokens via **Laravel Sanctum** para consumo por aplicações móveis ou sistemas externos de faturação:

- `POST /api/login`: Emissão de token de acesso.
- `GET /api/user`: Obtenção do perfil do utilizador autenticado.
- `GET /api/student/payments`: Consulta de pagamentos do encarregado via API.

---

## ⚙️ 4. Configuração no `.env`

```ini
# Configurações M-Pesa API
MPESA_API_KEY=sua_chave_api_mpesa
MPESA_PUBLIC_KEY=sua_chave_publica
MPESA_ENV=sandbox # ou production

# Configurações eMola API
EMOLA_CLIENT_ID=seu_client_id
EMOLA_CLIENT_SECRET=seu_client_secret
```
