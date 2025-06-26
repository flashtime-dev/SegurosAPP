import { useState } from 'react';

interface ImageZoomProps {
    src: string;
    alt?: string;
}

const ImageZoom = ({ src, alt = '' }: ImageZoomProps) => {
    const [open, setOpen] = useState(false);

    return (
        <>
            <img
                src={src}
                alt={alt}
                className="w-40 h-32 object-cover rounded cursor-pointer"
                onClick={() => setOpen(true)}
            />

            {open && (
                <div
                    className="fixed inset-0 z-50 bg-black bg-opacity-80 flex items-center justify-center"
                    onClick={() => setOpen(false)}
                >
                    <img
                        src={src}
                        alt={alt}
                        className="max-w-[90%] max-h-[90%] rounded shadow-lg"
                        onClick={(e) => e.stopPropagation()}
                    />
                    <button
                        className="absolute top-5 right-5 text-white text-3xl font-bold"
                        onClick={() => setOpen(false)}
                    >
                        ✕
                    </button>
                </div>
            )}
        </>
    );
};

export default ImageZoom;
