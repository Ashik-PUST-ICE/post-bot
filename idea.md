## 1. Project Overview

**SocialAgent** is a production-grade, multi-tenant SaaS platform that enables businesses to automate their Facebook Page and WhatsApp Business communications using AI .

### What It Does

```
Customer sends message/comment
         ↓
   Meta Webhook fires
         ↓
  Laravel receives event
         ↓
  Job queued (Redis)
         ↓
  AI generates reply (connent Ai )
         ↓
  Reply sent back via Meta API
         ↓
  Logged in database
         ↓
  Visible in dashboard
```

### Core Platforms Supported

| Platform | Feature |
|---|---|
| Facebook Page | Comment auto-reply |
| Facebook Messenger | DM auto-reply |
| WhatsApp Business | Message auto-reply |
| Instagram (Phase 2) | Comment + DM reply |

### 🔵 Platform Connections
- [x] Facebook Page connection (OAuth)
- [x] Facebook Messenger webhook
- [x] Facebook Comment monitoring + auto-reply
- [x] WhatsApp Business API connection
- [x] WhatsApp message auto-reply
- [x] Multi-page support per account
- [x] Multi-number WhatsApp support
- [x] Connection health monitoring
- [x] Auto-reconnect on token expiry

### 🤖 AI Agent Features
- [x] Claude AI integration (claude-sonnet-4-20250514)
- [x] Custom system prompt per business
- [x] Business context training (FAQs, products, tone)
- [x] Language auto-detection & multilingual reply
- [x] Sentiment analysis before reply
- [x] Smart reply suggestions (human can approve)
- [x] Auto-reply ON/OFF toggle per platform
- [x] Reply delay simulation (human-like timing)
- [x] Keyword-based rule triggers
- [x] Fallback to human if AI confidence low
- [x] Conversation memory (context-aware replies)
- [x] Spam detection & ignore

### 📊 Dashboard & Analytics
- [x] Real-time message feed (live updates)
- [x] Total messages received / replied
- [x] Response time metrics (avg, min, max)
- [x] Platform-wise breakdown charts
- [x] Sentiment trend graph (positive/negative/neutral)
- [x] Peak hours heatmap
- [x] Keyword frequency analysis
- [x] Failed/pending reply tracking
- [x] Export reports (CSV, PDF)
- [x] Date range filter

### 💬 Conversation Management
- [x] Inbox view (all messages in one place)
- [x] Thread view (full conversation history)
- [x] Manual reply override (human takes over)
- [x] Assign conversation to team member
- [x] Mark as resolved / pending / escalated
- [x] Internal notes on conversations
- [x] Tag/label conversations
- [x] Search conversations by keyword/contact

### 🔔 Notifications
- [x] Email notification on escalations
- [x] Browser push notifications
- [x] Slack/Discord webhook integration
- [x] Daily summary email report
- [x] Alert if reply fails

**System Settings**
- Global AI system prompt defaults


## 9. API Integration Setup

### 9.1 Facebook / Meta Setup

**Step 1 — Create Meta App**
```
1. Go to: https://developers.facebook.com/apps/
2. Click "Create App" → Choose "Business"
3. App name: SocialAgent
4. Add products: Messenger + Webhooks + Instagram Basic Display
```

**Step 2 — Configure Webhooks**
```
Webhook URL: https://app.socialagent.io/webhook/facebook
Verify Token: (match FB_VERIFY_TOKEN in .env)

Subscribe to:
  Pages → messages, messaging_postbacks, feed, comments
```

**Step 3 — Permissions needed**
```
pages_messaging
pages_manage_metadata
pages_read_engagement
pages_show_list
instagram_basic (Phase 2)
```

### 9.2 WhatsApp Business API Setup

**Step 1 — Meta Business Account**
```
1. Create Meta Business Account: business.facebook.com
2. Add a WhatsApp Business Account
3. Get a phone number (or use test number)
4. Enable Cloud API (free) at: developers.facebook.com
```

**Step 2 — Webhook**
```
Webhook URL: https://app.socialagent.io/webhook/whatsapp
Verify Token: (match WHATSAPP_VERIFY_TOKEN in .env)

Subscribe to: messages
```

**Step 3 — Required IDs**
```
PHONE_NUMBER_ID  → From WA Business API dashboard
WABA_ID          → WhatsApp Business Account ID
ACCESS_TOKEN     → Permanent token from System User
```

### 9.3 ngrok for Local Testing

```bash
# Install ngrok
brew install ngrok  # Mac
# or download from ngrok.com

# Start tunnel
ngrok http 8000

# Your public URL: https://abc123.ngrok.io
# Use this as your webhook URL during development
```
