import Navbar from "../components/Navbar";
import Footer from "../components/Footer";
import heroVume from "../assets/images/hero-vume.png";
import { api } from "../api";

import { useState, useEffect } from "react";

export default function VumePage() {
    const [vumeItems, setVumeItems] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        api("/vume")
            .then(setVumeItems)
            .catch(console.error)
            .finally(() => setLoading(false));
    }, []);
    return (
        <main className="min-h-screen flex flex-col bg-white">
            <Navbar />

            {/* Hero Vume */}
            <section className="pt-16 sm:pt-12">
                <img
                    src={heroVume}
                    alt="Vume Hero"
                    className="w-full h-auto object-cover"
                />
            </section>

            {/* Bestseller Section */}
            <section className="flex-1 px-4 sm:px-6 py-8 sm:py-12">
                <div className="text-center">
                    <p className="text-[10px] sm:text-xs tracking-[3px] text-gray-500 font-semibold">BATCH 1</p>
                    <h2 className="mt-1 sm:mt-2 text-2xl sm:text-4xl font-light tracking-wide text-gray-900">
                        OUR BESTSELLERS
                    </h2>
                </div>
                {loading && (
                    <p className="text-center text-sm text-gray-400 mt-10">Memuat...</p>
                )}

                {/* Dynamic List Parfum */}
                <div className="space-y-12 mt-10 sm:mt-16">
                    {vumeItems.map((item, index) => (
                        <div key={item.id}>
                            <div
                                className={`mx-auto flex flex-col ${index % 2 === 1 ? "md:flex-row-reverse" : "md:flex-row"
                                    } max-w-6xl items-center gap-8 md:gap-12`}
                            >
                                {/* Container Gambar Parfum (Tanpa Background Warna) */}
                                <div className="h-64 w-64 sm:h-72 sm:w-72 shrink-0 overflow-hidden flex items-center justify-center p-2">
                                    {item.image_url ? (
                                        <img
                                            src={item.image_url}
                                            alt={item.title}
                                            className="w-full h-full object-contain"
                                        />
                                    ) : (
                                        <div className="w-full h-full border border-dashed border-gray-300 rounded-xl flex items-center justify-center text-center p-4">
                                            <span className="text-xs text-gray-400 font-medium">Belum ada gambar botol</span>
                                        </div>
                                    )}
                                </div>

                                {/* Detail Parfum */}
                                <div className="text-center md:text-left flex-1">
                                    <h3 className="text-3xl sm:text-5xl font-medium tracking-tight text-gray-900">
                                        {item.title}
                                    </h3>

                                    <p className="mt-1 italic text-gray-600 text-sm sm:text-base">
                                        {item.subtitle}
                                    </p>

                                    <div className="mt-4 sm:mt-6 text-xs sm:text-sm text-gray-700 space-y-1">
                                        <p className="font-semibold text-gray-900">Note</p>
                                        <p>Top : {item.top}</p>
                                        <p>Middle : {item.middle}</p>
                                        <p>Base : {item.base}</p>
                                    </div>

                                    <p className="mt-4 sm:mt-6 text-xs sm:text-sm font-medium text-gray-800">
                                        Size : {item.size}
                                    </p>
                                </div>
                            </div>

                            {/* Tagline di antara produk pertama dan kedua */}
                            {index === 0 && (
                                <div className="my-10 sm:my-16 flex flex-wrap justify-center items-center gap-3 sm:gap-8 text-sm sm:text-xl text-gray-600 font-light">
                                    <span>Eau De Parfume</span>
                                    <span className="hidden sm:inline">|</span>
                                    <span>Home Made</span>
                                    <span className="hidden sm:inline">|</span>
                                    <span>Intimate</span>
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            </section>

            {/* Footer */}
            <Footer />
        </main>
    );
}