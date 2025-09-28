const mongoose = require("mongoose");

const dbConnect = async () => {
    try {
        await mongoose.connect("mongodb://127.0.0.1:27017/gudsky_contacts", {
            useNewUrlParser: true,
            useUnifiedTopology: true,
        });
        console.log("MongoDB connected successfully 🚀");
    } catch (error) {
        console.error("MongoDB connection failed ❌", error);
        process.exit(1);
    }
};

module.exports = dbConnect;
