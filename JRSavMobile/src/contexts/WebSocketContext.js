// JRSavMobile/src/contexts/WebSocketContext.js
import React, { createContext, useContext, useEffect } from 'react';
import { useAuth } from './AuthContext';
import useWebSocket from '../hooks/useWebSocket';
import { Alert } from 'react-native';

const WebSocketContext = createContext({});

export const useWebSocketContext = () => useContext(WebSocketContext);

export const WebSocketProvider = ({ children }) => {
  const { user } = useAuth();

  const handleTicketAssigned = (data) => {
    Alert.alert(
      'Nouveau ticket SAV',
      `${data.message || `Ticket ${data.ticket_number} assigné`}`,
      [{ text: 'OK' }]
    );
  };

  const handleCriticalPart = (data) => {
    Alert.alert('⚠️ Stock critique', data.message || 'Une pièce est en stock critique', [{ text: 'OK' }]);
  };

  const handleUrgentTicket = (data) => {
    Alert.alert('🚨 URGENT', data.message || 'Ticket prioritaire à traiter', [{ text: 'OK' }]);
  };

  const { isConnected, connectionError, reconnect } = useWebSocket(
    user?.id,
    handleTicketAssigned,
    handleCriticalPart,
    handleUrgentTicket
  );

  useEffect(() => {
    if (connectionError) console.warn('WebSocket error:', connectionError);
  }, [connectionError]);

  return (
    <WebSocketContext.Provider value={{ isConnected, connectionError, reconnect }}>
      {children}
    </WebSocketContext.Provider>
  );
};