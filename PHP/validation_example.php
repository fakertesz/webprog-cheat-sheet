<?php
// Initialize variables
$errors = [];
$formData = [
    'name' => '',
    'email' => '',
    'birthdate' => '',
    'country' => '',
    'phone' => '',
    'postal_code' => '',
    'age' => '',
    'website' => ''
];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $formData = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'birthdate' => trim($_POST['birthdate'] ?? ''),
        'country' => $_POST['country'] ?? '',
        'phone' => trim($_POST['phone'] ?? ''),
        'postal_code' => trim($_POST['postal_code'] ?? ''),
        'age' => trim($_POST['age'] ?? ''),
        'website' => trim($_POST['website'] ?? '')
    ];

    // Name validation (letters, spaces, hyphens only, 2-50 characters)
    if (empty($formData['name'])) {
        $errors['name'] = 'Name is required';
    } elseif (!preg_match('/^[a-zA-Z\s\-]{2,50}$/', $formData['name'])) {
        $errors['name'] = 'Name must contain only letters, spaces, and hyphens (2-50 characters)';
    }

    // Email validation
    if (empty($formData['email'])) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    // Date validation (birthdate must be in the past and person must be at least 18)
    if (empty($formData['birthdate'])) {
        $errors['birthdate'] = 'Birthdate is required';
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $formData['birthdate']);
        if (!$date || $date->format('Y-m-d') !== $formData['birthdate']) {
            $errors['birthdate'] = 'Invalid date format';
        } else {
            $today = new DateTime();
            $age = $today->diff($date)->y;
            if ($date > $today) {
                $errors['birthdate'] = 'Birthdate cannot be in the future';
            } elseif ($age < 18) {
                $errors['birthdate'] = 'You must be at least 18 years old';
            }
        }
    }

    // Select validation (country must be selected)
    if (empty($formData['country'])) {
        $errors['country'] = 'Please select a country';
    }

    // Phone validation (international format: +XX-XXX-XXX-XXXX or similar)
    if (empty($formData['phone'])) {
        $errors['phone'] = 'Phone number is required';
    } elseif (!preg_match('/^\+?[0-9]{1,3}[-\s]?[0-9]{2,4}[-\s]?[0-9]{3,4}[-\s]?[0-9]{3,4}$/', $formData['phone'])) {
        $errors['phone'] = 'Invalid phone format (e.g., +36-20-123-4567)';
    }

    // Postal code validation (5 digits)
    if (empty($formData['postal_code'])) {
        $errors['postal_code'] = 'Postal code is required';
    } elseif (!preg_match('/^[0-9]{5}$/', $formData['postal_code'])) {
        $errors['postal_code'] = 'Postal code must be exactly 5 digits';
    }

    // Age validation (number between 18 and 120)
    if (empty($formData['age'])) {
        $errors['age'] = 'Age is required';
    } elseif (!preg_match('/^[0-9]+$/', $formData['age'])) {
        $errors['age'] = 'Age must be a number';
    } elseif ($formData['age'] < 18 || $formData['age'] > 120) {
        $errors['age'] = 'Age must be between 18 and 120';
    }

    // Website validation (optional but must be valid URL if provided)
    if (!empty($formData['website']) && !filter_var($formData['website'], FILTER_VALIDATE_URL)) {
        $errors['website'] = 'Invalid URL format (e.g., https://example.com)';
    }

    // If no errors, process the form
    if (empty($errors)) {
        $successMessage = 'Form submitted successfully!';
        // Here you would typically save to database or process the data
    }
}

// Available countries for the select
$countries = [
    '' => '-- Select Country --',
    'HU' => 'Hungary',
    'US' => 'United States',
    'UK' => 'United Kingdom',
    'DE' => 'Germany',
    'FR' => 'France',
    'IT' => 'Italy',
    'ES' => 'Spain'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Validation Examples</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }

        .required {
            color: #e74c3c;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"],
        input[type="url"],
        select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
        }

        input.error,
        select.error {
            border-color: #e74c3c;
        }

        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .hint {
            color: #999;
            font-size: 12px;
            margin-top: 3px;
            font-style: italic;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(0);
        }

        .success-message {
            background: #2ecc71;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .validation-list {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .validation-list h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .validation-list ul {
            list-style-position: inside;
            color: #666;
            font-size: 13px;
        }

        .validation-list li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP Validation Examples</h1>
        <p class="subtitle">Comprehensive form with various validation types</p>

        <?php if (isset($successMessage)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <div class="validation-list">
            <h3>Validation Types Demonstrated:</h3>
            <ul>
                <li><strong>Name:</strong> Letters, spaces, hyphens only (2-50 chars)</li>
                <li><strong>Email:</strong> Valid email format</li>
                <li><strong>Birthdate:</strong> Valid date, must be 18+ years old</li>
                <li><strong>Country:</strong> Must select from dropdown</li>
                <li><strong>Phone:</strong> International format with regex</li>
                <li><strong>Postal Code:</strong> Exactly 5 digits</li>
                <li><strong>Age:</strong> Number between 18-120</li>
                <li><strong>Website:</strong> Valid URL format (optional)</li>
            </ul>
        </div>

        <form method="POST" action="">
            <!-- Name Field -->
            <div class="form-group">
                <label for="name">
                    Full Name <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?php echo htmlspecialchars($formData['name']); ?>"
                    class="<?php echo isset($errors['name']) ? 'error' : ''; ?>"
                    placeholder="John Doe"
                >
                <?php if (isset($errors['name'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['name']); ?></span>
                <?php else: ?>
                    <span class="hint">Letters, spaces, and hyphens only</span>
                <?php endif; ?>
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label for="email">
                    Email Address <span class="required">*</span>
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?php echo htmlspecialchars($formData['email']); ?>"
                    class="<?php echo isset($errors['email']) ? 'error' : ''; ?>"
                    placeholder="john.doe@example.com"
                >
                <?php if (isset($errors['email'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['email']); ?></span>
                <?php else: ?>
                    <span class="hint">Valid email format required</span>
                <?php endif; ?>
            </div>

            <!-- Birthdate Field -->
            <div class="form-group">
                <label for="birthdate">
                    Date of Birth <span class="required">*</span>
                </label>
                <input
                    type="date"
                    id="birthdate"
                    name="birthdate"
                    value="<?php echo htmlspecialchars($formData['birthdate']); ?>"
                    class="<?php echo isset($errors['birthdate']) ? 'error' : ''; ?>"
                >
                <?php if (isset($errors['birthdate'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['birthdate']); ?></span>
                <?php else: ?>
                    <span class="hint">Must be 18 years or older</span>
                <?php endif; ?>
            </div>

            <!-- Country Select -->
            <div class="form-group">
                <label for="country">
                    Country <span class="required">*</span>
                </label>
                <select
                    id="country"
                    name="country"
                    class="<?php echo isset($errors['country']) ? 'error' : ''; ?>"
                >
                    <?php foreach ($countries as $code => $name): ?>
                        <option
                            value="<?php echo htmlspecialchars($code); ?>"
                            <?php echo $formData['country'] === $code ? 'selected' : ''; ?>
                        >
                            <?php echo htmlspecialchars($name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['country'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['country']); ?></span>
                <?php else: ?>
                    <span class="hint">Select your country from the list</span>
                <?php endif; ?>
            </div>

            <!-- Phone Field -->
            <div class="form-group">
                <label for="phone">
                    Phone Number <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="<?php echo htmlspecialchars($formData['phone']); ?>"
                    class="<?php echo isset($errors['phone']) ? 'error' : ''; ?>"
                    placeholder="+36-20-123-4567"
                >
                <?php if (isset($errors['phone'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['phone']); ?></span>
                <?php else: ?>
                    <span class="hint">Format: +XX-XXX-XXX-XXXX</span>
                <?php endif; ?>
            </div>

            <!-- Postal Code Field -->
            <div class="form-group">
                <label for="postal_code">
                    Postal Code <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="postal_code"
                    name="postal_code"
                    value="<?php echo htmlspecialchars($formData['postal_code']); ?>"
                    class="<?php echo isset($errors['postal_code']) ? 'error' : ''; ?>"
                    placeholder="12345"
                    maxlength="5"
                >
                <?php if (isset($errors['postal_code'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['postal_code']); ?></span>
                <?php else: ?>
                    <span class="hint">5 digits required</span>
                <?php endif; ?>
            </div>

            <!-- Age Field -->
            <div class="form-group">
                <label for="age">
                    Age <span class="required">*</span>
                </label>
                <input
                    type="number"
                    id="age"
                    name="age"
                    value="<?php echo htmlspecialchars($formData['age']); ?>"
                    class="<?php echo isset($errors['age']) ? 'error' : ''; ?>"
                    placeholder="25"
                    min="18"
                    max="120"
                >
                <?php if (isset($errors['age'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['age']); ?></span>
                <?php else: ?>
                    <span class="hint">Must be between 18 and 120</span>
                <?php endif; ?>
            </div>

            <!-- Website Field (Optional) -->
            <div class="form-group">
                <label for="website">
                    Website (Optional)
                </label>
                <input
                    type="url"
                    id="website"
                    name="website"
                    value="<?php echo htmlspecialchars($formData['website']); ?>"
                    class="<?php echo isset($errors['website']) ? 'error' : ''; ?>"
                    placeholder="https://example.com"
                >
                <?php if (isset($errors['website'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['website']); ?></span>
                <?php else: ?>
                    <span class="hint">Must be a valid URL if provided</span>
                <?php endif; ?>
            </div>

            <button type="submit">Submit Form</button>
        </form>
    </div>
</body>
</html>
