const express = require("express");
const mongoose = require("mongoose");
const contactRoutes = require("./routes/contactRoutes");
const dbConnect = require("./config/db");
const cors = require("cors");

const app = express();

// Middleware
app.use(express.json());
app.use(cors());

// Connect DB
dbConnect();

// Routes
app.use("/api/contact", contactRoutes);

// Start server
const PORT = 5000;
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
