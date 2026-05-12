# Users
+ user_id (string:64)
* email (string:100) unique
* password_hash (string)
* two_factor_enabled (bool) = false
* biometric_token (string?)
* role (string:20) = user // user, advisor, manager
* created_at (datetime)

# User_Dashboards
+ dash_board_id (string:64)
+ user_id -> Users.users_id
* balance (float) = 0.00
* total_spending (float) = 0.00

# Transactions
+ transaction_id (string:64)
* user_id -> Users.users_id
* amount (float)
* type (string:10) // in, out
* category (string:50)
* description (string?)
* transaction_date (datetime)
* receipt_image_url (string?)

# Budgets
+ budget_id (string:64)
* user_id -> Users.users_id
* category (string:50)
* limit_amount (float)
* current_spending (float) = 0.00
* notification_threshold (float) = 0.8 // 80%

# Savings_Pots
+ saving_id (string:64)
* user_id -> Users.users_id
* name (string:50)
* target_amount (float?)
* current_balance (float) = 0.00
* is_long_term (bool) = false

# Advisors
+ advisor_id (string:64)
* user_id -> Users.users_id
* specialization (string:100)

# Clients
+ client_id (string:64)
* advisor_id -> Advisors.advisors_id
* user_id -> Users.users_id
* status (string:20) = active // active, deleted_in_bin
* notes (text)

# Reports
+ report_id (string)
* manager_id -> Users.users_id
* title (string:100)
* type (string:20) // ad_hoc, predictive, historical
* content (text)
* generated_at (datetime)

# Tokens
+ user_id -> Users.user_id
+ token (string:64)
* created_on (datetime)
