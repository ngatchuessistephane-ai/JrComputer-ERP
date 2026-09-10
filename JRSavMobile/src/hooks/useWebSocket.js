// JRSavMobile/src/hooks/useWebSocket.js
import { useState } from 'react';

export const useWebSocket = (userId, onTicketAssigned, onCriticalPart, onUrgentTicket) => {
  const [isConnected] = useState(false);
  const [connectionError] = useState(null);
  const reconnect = () => {};
  return { isConnected, connectionError, reconnect };
};

export default useWebSocket;