using System;
using System.IO;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;

namespace MerchantApi
{

    /// <summary>
    /// A data class for storing the contents of merchant-info.json.
    /// </summary>
    internal class MerchantConfig
    {
        public string ConfigDir { get; set; }
        internal string ConfigFile { get; set; }

        [Newtonsoft.Json.JsonPropertyAttribute("merchantId")]
        public ulong? MerchantId { get; set; }

        public static MerchantConfig Load()
        {
            MerchantConfig config;
            var homePath = Environment.GetFolderPath(Environment.SpecialFolder.UserProfile);
            var configPath = "shopping-samples";
            var contentPath = Path.Combine(homePath, configPath, "content");
            if (!Directory.Exists(contentPath))
            {
                Console.WriteLine($"Could not find configuration directory at {contentPath}");
                Console.WriteLine("Please read the included README for instructions.");
                throw new FileNotFoundException("Missing configuration directory");
            }

            var contentFile = Path.Combine(contentPath, "merchant-info.json");
            if (!File.Exists(contentFile))
            {
                Console.WriteLine($"No configuration file at {contentFile}");
                Console.WriteLine("Assuming default configuration for authenticated user.");
                config = new MerchantConfig {
                  ConfigDir = contentPath
                };
                return config;
            }
            using (StreamReader reader = File.OpenText(contentFile))
            {
                config = (MerchantConfig)JToken.ReadFrom(new JsonTextReader(reader))
                    .ToObject(typeof(MerchantConfig));
                config.ConfigDir = contentPath;
                config.ConfigFile = contentFile;
            }

            return config;
        }

    }

}