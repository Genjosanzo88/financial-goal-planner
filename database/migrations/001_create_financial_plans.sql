CREATE TABLE financial_plans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(150) NOT NULL,
    goal_name VARCHAR(150) NOT NULL,
    target_amount DECIMAL(15, 2) NOT NULL,
    initial_capital DECIMAL(15, 2) NOT NULL,
    monthly_contribution DECIMAL(15, 2) NOT NULL,
    years SMALLINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);