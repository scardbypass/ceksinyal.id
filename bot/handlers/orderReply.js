import { replyOrder } from '../services/websiteApi.js';
export async function handleOrderReply(message){const text=(message.text||'').trim().toUpperCase();if(!['D','DONE','F','FAILED'].includes(text))return false;const quoted=message.quotedText||'';const m=quoted.match(/Order\s*:\s*(ORD-[A-Z0-9-]+)/i);if(!m)return false;await replyOrder(m[1],text);return true;}
