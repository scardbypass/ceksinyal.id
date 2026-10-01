import { replyOrder } from '../services/websiteApi.js';
function textOf(m){return m.message?.conversation||m.message?.extendedTextMessage?.text||''}
function quotedOf(m){return m.message?.extendedTextMessage?.contextInfo?.quotedMessage?.conversation||m.message?.extendedTextMessage?.contextInfo?.quotedMessage?.extendedTextMessage?.text||''}
export async function handleOrderReply(sock,m){if(!m?.message||m.key?.fromMe)return false;const text=textOf(m).trim().toUpperCase();if(!['D','DONE','F','FAILED'].includes(text))return false;const hit=quotedOf(m).match(/Order\s*:\s*#?(ORD-[A-Z0-9-]+)/i);if(!hit)return false;await replyOrder(hit[1],text);await sock.sendMessage(m.key.remoteJid,{text:`✓ ${hit[1]} updated`},{quoted:m});return true;}
