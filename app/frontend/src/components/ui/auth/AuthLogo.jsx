import logoIcon from '../../../../../../public/images/logo/taskgroup-logo-icon.png';

export default function AuthLogo() {
    return (
        <div className="logo-container">
            <img
                src={logoIcon}
                alt="TaskGroup Logo"
                className="logo-img"
                style={{ width: 'max-width', height: '200px' }}
            ></img>
            <span className="logo-text">
                <span className="text-tg-primary fs-1 fw-bold">TASK</span>
                <span className="text-tg-secondary fs-1 fw-bold">GROUP</span>
            </span>
        </div>
    );
}