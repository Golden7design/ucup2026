import { useEffect, useRef } from 'react';
import gsap from 'gsap';

interface AnimatedNumberProps {
    value: number;
    duration?: number;
    format?: (n: number) => string;
    className?: string;
}

export function AnimatedNumber({
    value,
    duration = 1.5,
    format = (n) => Math.round(n).toString(),
    className = '',
}: AnimatedNumberProps) {
    const elementRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!elementRef.current) return;

        const obj = { value: 0 };
        gsap.to(obj, {
            value,
            duration,
            onUpdate: () => {
                if (elementRef.current) {
                    elementRef.current.textContent = format(obj.value);
                }
            },
            ease: 'power2.out',
        });
    }, [value, duration, format]);

    return <div ref={elementRef} className={className} />;
}

interface FadeInProps {
    children: React.ReactNode;
    delay?: number;
    duration?: number;
    className?: string;
}

export function FadeIn({
    children,
    delay = 0,
    duration = 0.6,
    className = '',
}: FadeInProps) {
    const elementRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!elementRef.current) return;

        gsap.fromTo(
            elementRef.current,
            { opacity: 0, y: 20 },
            {
                opacity: 1,
                y: 0,
                duration,
                delay,
                ease: 'power2.out',
            }
        );
    }, [delay, duration]);

    return (
        <div ref={elementRef} className={className}>
            {children}
        </div>
    );
}

interface ScaleInProps {
    children: React.ReactNode;
    delay?: number;
    duration?: number;
    className?: string;
}

export function ScaleIn({
    children,
    delay = 0,
    duration = 0.5,
    className = '',
}: ScaleInProps) {
    const elementRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!elementRef.current) return;

        gsap.fromTo(
            elementRef.current,
            { opacity: 0, scale: 0.9 },
            {
                opacity: 1,
                scale: 1,
                duration,
                delay,
                ease: 'back.out',
            }
        );
    }, [delay, duration]);

    return (
        <div ref={elementRef} className={className}>
            {children}
        </div>
    );
}

interface SlideInProps {
    children: React.ReactNode;
    direction?: 'left' | 'right' | 'up' | 'down';
    delay?: number;
    duration?: number;
    className?: string;
}

export function SlideIn({
    children,
    direction = 'left',
    delay = 0,
    duration = 0.6,
    className = '',
}: SlideInProps) {
    const elementRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!elementRef.current) return;

        const fromVars: Record<string, number> = {
            opacity: 0,
        };

        switch (direction) {
            case 'left':
                fromVars.x = -50;
                break;
            case 'right':
                fromVars.x = 50;
                break;
            case 'up':
                fromVars.y = 50;
                break;
            case 'down':
                fromVars.y = -50;
                break;
        }

        gsap.fromTo(
            elementRef.current,
            fromVars,
            {
                opacity: 1,
                x: 0,
                y: 0,
                duration,
                delay,
                ease: 'power2.out',
            }
        );
    }, [direction, delay, duration]);

    return (
        <div ref={elementRef} className={className}>
            {children}
        </div>
    );
}

interface CountUpProps {
    from: number;
    to: number;
    duration?: number;
    format?: (n: number) => string;
    className?: string;
}

export function CountUp({
    from,
    to,
    duration = 2,
    format = (n) => Math.round(n).toString(),
    className = '',
}: CountUpProps) {
    const elementRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!elementRef.current) return;

        const obj = { value: from };
        gsap.to(obj, {
            value: to,
            duration,
            onUpdate: () => {
                if (elementRef.current) {
                    elementRef.current.textContent = format(obj.value);
                }
            },
            ease: 'power2.inOut',
        });
    }, [from, to, duration, format]);

    return <div ref={elementRef} className={className} />;
}

interface PulseProps {
    children: React.ReactNode;
    className?: string;
    scale?: number;
    duration?: number;
}

export function Pulse({
    children,
    className = '',
    scale = 1.05,
    duration = 0.6,
}: PulseProps) {
    const elementRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!elementRef.current) return;

        gsap.to(elementRef.current, {
            scale,
            duration,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
        });
    }, [scale, duration]);

    return (
        <div ref={elementRef} className={className}>
            {children}
        </div>
    );
}

interface RotateProps {
    children: React.ReactNode;
    className?: string;
    duration?: number;
}

export function Rotate({
    children,
    className = '',
    duration = 2,
}: RotateProps) {
    const elementRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!elementRef.current) return;

        gsap.to(elementRef.current, {
            rotation: 360,
            duration,
            repeat: -1,
            ease: 'none',
        });
    }, [duration]);

    return (
        <div ref={elementRef} className={className}>
            {children}
        </div>
    );
}

interface FlipProps {
    children: React.ReactNode;
    className?: string;
    duration?: number;
}

export function Flip({
    children,
    className = '',
    duration = 0.6,
}: FlipProps) {
    const elementRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!elementRef.current) return;

        gsap.to(elementRef.current, {
            rotationY: 360,
            duration,
            ease: 'back.out',
        });
    }, [duration]);

    return (
        <div ref={elementRef} className={className}>
            {children}
        </div>
    );
}
