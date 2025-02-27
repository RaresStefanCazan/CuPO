# CuPO

**CuPO** is a comprehensive web-based application built with PHP. It follows a RESTful design approach, ensuring seamless integration with other services. CuPO also offers advanced user management and security features, making it a robust solution for various data-driven needs.

## Key Features

- **Security:** User passwords and data are securely hashed, and sessions are validated at each login to protect private data.  
- **User Profiles:** Store dietary preferences, health goals, and restrictions, enabling personalized recommendations for diets and food items.  
- **Family Groups:** Create groups for families or communities. Members can share a **real-time shopping cart**, allowing everyone to add items collaboratively.  
- **Data Export:** Generate CSV and PDF files (via [TCPDF](https://tcpdf.org/)) for reporting or archiving.  
- **RESTful Endpoints:** Standard HTTP methods (GET, POST, PUT, DELETE) ensure smooth communication with external services.

## Installation

1. **Set Up XAMPP**  
   - Download and install [XAMPP](https://www.apachefriends.org/download.html).  
   - Make sure **Apache** and **MySQL** are running in the XAMPP Control Panel.

2. **Clone the Repository**  
   ```bash
   git clone https://github.com/RaresStefanCazan/CuPO.git

   Place the cloned project folder in the htdocs directory of your XAMPP installation (e.g., C:\xampp\htdocs\CuPO on Windows).
