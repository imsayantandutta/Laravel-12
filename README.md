This telemedicine application uses a responsive and customized UI template. The home page displays medicines through a dynamic slider, with all product data fetched from the database and managed in a centralized product table.

Users can purchase medicines by clicking the Buy option, which adds the product to the checkout page along with an order summary. After successful payment, users are redirected to a Thank You page.

The system supports both question-based and normal medicines. Question-based medicines require users to complete a medical questionnaire before placing an order; incomplete flows result in partial orders, while completed flows create successful orders. Normal medicines are placed directly without any questions.

The application is integrated with Vrio CRM to create and manage orders with complete or partial status based on user actions. For complete orders, patient profiles are created or updated in the Telegra Portal and medicines are assigned accordingly. Partial orders do not trigger patient profile creation or medicine assignment.