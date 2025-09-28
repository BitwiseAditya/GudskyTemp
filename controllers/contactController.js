const Contact = require("../models/Contact");

// Save contact
const saveContact = async (req, res) => {
    try {
        const { fullName, email, message } = req.body;

        if (!fullName || !email || !message) {
            return res.status(400).json({ error: "All fields are required" });
        }

        const newContact = new Contact({ fullName, email, message });
        await newContact.save();

        res.status(201).json({ message: "Message received! We'll contact you soon." });
    } catch (error) {
        console.error("Error saving contact:", error);
        res.status(500).json({ error: "Internal server error" });
    }
};

module.exports = { saveContact };
