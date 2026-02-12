<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <ul class="progress-indicator m-4">
                    <!-- Étape 1: Identité -->
                    <li
                        class="{{ in_array(Route::currentRouteName(), [
                            'transaction.reservation.createIdentity',
                            'transaction.reservation.pickFromCustomer',
                            'transaction.reservation.viewCountPerson',
                            'transaction.reservation.choose-type',
                            'transaction.reservation.confirmation-type',
                            'transaction.reservation.payDownPayment'
                        ]) ? 'completed' : '' }}">
                        <span class="bubble"></span> 
                        <span class="step-title">
                            <i class="fas fa-user me-1"></i> Identité
                        </span>
                    </li>
                    
                    <!-- Étape 2: Type de chambre -->
                    <li
                        class="{{ in_array(Route::currentRouteName(), [
                            'transaction.reservation.viewCountPerson',
                            'transaction.reservation.choose-type',
                            'transaction.reservation.confirmation-type',
                            'transaction.reservation.payDownPayment'
                        ]) ? 'completed' : '' }}">
                        <span class="bubble"></span>
                        <span class="step-title">
                            <i class="fas fa-tag me-1"></i> Type de chambre
                        </span>
                    </li>
                    
                    <!-- Étape 3: Dates & Personnes -->
                    <li
                        class="{{ in_array(Route::currentRouteName(), [
                            'transaction.reservation.confirmation-type',
                            'transaction.reservation.payDownPayment'
                        ]) ? 'completed' : '' }}">
                        <span class="bubble"></span>
                        <span class="step-title">
                            <i class="fas fa-calendar-alt me-1"></i> Dates & Personnes
                        </span>
                    </li>
                    
                    <!-- Étape 4: Confirmation -->
                    <li
                        class="{{ in_array(Route::currentRouteName(), [
                            'transaction.reservation.payDownPayment'
                        ]) ? 'completed' : '' }}">
                        <span class="bubble"></span>
                        <span class="step-title">
                            <i class="fas fa-check-circle me-1"></i> Confirmation
                        </span>
                    </li>
                </ul>
                
                <!-- Explication du nouveau système -->
                <div class="alert alert-info alert-sm mt-3 mb-0 border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <div class="small">
                            <strong>Nouveau système :</strong> Vous choisissez un <strong>type</strong> de chambre (Standard, Premium, Suite). 
                            Le numéro de chambre sera attribué lors du check-in.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.progress-indicator {
    display: flex;
    justify-content: space-between;
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
}

.progress-indicator:before {
    content: '';
    position: absolute;
    top: 15px;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: #e0e0e0;
    z-index: 1;
}

.progress-indicator li {
    text-align: center;
    position: relative;
    z-index: 2;
    flex: 1;
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 500;
    padding: 0 10px;
}

.progress-indicator li.completed {
    color: #4a6fa5;
}

.progress-indicator li.completed .bubble {
    background-color: #4a6fa5;
    border-color: #4a6fa5;
}

.progress-indicator li.completed .bubble:after {
    content: '✓';
    color: white;
    font-size: 0.8rem;
    font-weight: bold;
}

.progress-indicator li .bubble {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: white;
    border: 2px solid #e0e0e0;
    display: block;
    margin: 0 auto 8px;
    position: relative;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.progress-indicator li .step-title {
    display: block;
    font-size: 0.85rem;
}

.alert-sm {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .progress-indicator {
        flex-wrap: wrap;
    }
    
    .progress-indicator li {
        flex: 0 0 50%;
        margin-bottom: 1rem;
    }
    
    .progress-indicator:before {
        display: none;
    }
}
</style>