import AsyncStorage from '@react-native-async-storage/async-storage';

export const savePendingReport = async (ticketId, report) => {
  const pending = await getPendingReports();
  pending.push({ ticketId, report, timestamp: Date.now() });
  await AsyncStorage.setItem('pending_reports', JSON.stringify(pending));
};

export const getPendingReports = async () => {
  const data = await AsyncStorage.getItem('pending_reports');
  return data ? JSON.parse(data) : [];
};

export const removePendingReport = async (index) => {
  const pending = await getPendingReports();
  pending.splice(index, 1);
  await AsyncStorage.setItem('pending_reports', JSON.stringify(pending));
};

export const clearAllPendingReports = async () => {
  await AsyncStorage.removeItem('pending_reports');
};