const { MongoClient, ObjectId } = require('mongodb');

// URL de connexion à votre base de données MongoDB
const url = 'mongodb://localhost:27017';
const dbName = 'votre_base_de_donnees';
const collectionName = 'interventions';

// Fonction pour établir une connexion à la base de données
async function connectDB() {
  const client = new MongoClient(url, { useNewUrlParser: true, useUnifiedTopology: true });

  try {
    await client.connect();
    console.log('Connexion à la base de données établie.');
    return client.db(dbName).collection(collectionName);
  } catch (error) {
    console.error('Erreur de connexion à la base de données :', error);
    throw error.
  }
}

// Fonction pour insérer une nouvelle intervention
async function insertIntervention(intervention) {
  const collection = await connectDB();
  const result = await collection.insertOne(intervention);
  console.log(`Intervention insérée avec l'ID : ${result.insertedId}`);
}

// Fonction pour rechercher les interventions pour un patient donné
async function findInterventionsForPatient(patientId) {
  const collection = await connectDB();
  const interventions = await collection.find({ patient_id: patientId }).toArray();
  console.log('Interventions pour le patient', patientId, ':', interventions);
}

// Fonction pour mettre à jour les détails d une intervention
async function updateIntervention(interventionId, update) {
  const collection = await connectDB();
  await collection.updateOne({ _id: ObjectId(interventionId) }, { $set: update });
  console.log('Intervention mise à jour avec succès.');
}

// Fonction pour supprimer une intervention
async function deleteIntervention(interventionId) {
  const collection = await connectDB();
  await collection.deleteOne({ _id: ObjectId(interventionId) });
  console.log('Intervention supprimée avec succès.');
}

// Exemples d utilisation
const newIntervention = {
  patient_id: '12345',
  date_intervention: '2023-04-01',
  type_intervention: 'Prélèvement de moelle osseuse',
  medecin: {
    nom: 'Dr. Dupont',
    specialite: 'Cardiologue'
  },
  notes: 'Le patient a subi un prélèvement de moelle osseuse. Aucune complication à signaler.'
};

insertIntervention(newIntervention);
findInterventionsForPatient('12345');
updateIntervention('5f8f2536f5de0a001ae8ae5a', { notes: 'Le patient récupère bien après la chirurgie.' });
deleteIntervention('5f8f2536f5de0a001ae8ae5a');
